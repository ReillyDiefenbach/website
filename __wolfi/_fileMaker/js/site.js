(function(){

    let activeScrollHandler = null;
    let scrollFrame = null;

    function loadScriptOnce(src, id = null){
        return new Promise((resolve, reject) => {
            const selector = id ? `script[data-loader-id="${id}"]` : `script[src="${src}"]`;
            const existing = document.querySelector(selector);

            if(existing){
                if(existing.dataset.loaded === '1') resolve();
                else existing.addEventListener('load', () => resolve(), {once:true});
                return;
            }

            const script = document.createElement('script');
            script.src = src;
            script.async = false;
            if(id) script.dataset.loaderId = id;
            script.addEventListener('load', () => {
                script.dataset.loaded = '1';
                resolve();
            }, {once:true});
            script.addEventListener('error', () => reject(new Error(`Script could not be loaded: ${src}`)), {once:true});
            document.head.appendChild(script);
        });
    }

    const MediaFallback = {
        image: '/_assets/fallback/image.jpg',
        video: '/_assets/fallback/video.mp4',
        observer: null,

        init(){
            this.prepare(document);

            if(this.observer || !window.MutationObserver) return;

            this.observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if(node instanceof Element) this.prepare(node);
                    });
                });
            });
            this.observer.observe(document.documentElement, {childList:true, subtree:true});
        },

        prepare(scope){
            const root = scope instanceof Element || scope instanceof Document ? scope : document;
            const media = root.matches?.('img, video')
                ? [root]
                : Array.from(root.querySelectorAll('img, video'));

            media.forEach(element => this.bind(element));
        },

        bind(element){
            if(element.dataset.fallbackBound === 'true') return;
            element.dataset.fallbackBound = 'true';

            if(element instanceof HTMLImageElement){
                element.addEventListener('error', () => this.useImage(element));
                if(element.complete && element.naturalWidth === 0) this.useImage(element);
                return;
            }

            if(element instanceof HTMLVideoElement){
                const useFallback = () => this.useVideo(element);
                element.addEventListener('error', useFallback);
                element.querySelectorAll('source').forEach(source => {
                    source.addEventListener('error', useFallback);
                });
                if(element.networkState === HTMLMediaElement.NETWORK_NO_SOURCE) useFallback();
            }
        },

        useImage(image){
            if(image.dataset.fallbackApplied === 'true' || image.currentSrc.endsWith(this.image)) return false;

            image.dataset.fallbackApplied = 'true';
            image.src = this.image;
            return true;
        },

        useVideo(video){
            if(video.dataset.fallbackApplied === 'true' || video.currentSrc.endsWith(this.video)) return false;

            video.dataset.fallbackApplied = 'true';
            video.src = this.video;
            video.load();
            video.play().catch(() => {});
            return true;
        }
    };

    const Preloader = {
        timeout: 30000,

        show(){
            document.documentElement.classList.add('isLoading');
            this.setProgress(0);
        },

        hide(){
            this.setProgress(1);
            document.documentElement.classList.remove('isLoading');
        },

        setProgress(progress){
            const value = Math.min(Math.max(progress, 0), 1);
            const preloader = document.querySelector('.wolf-preloader');
            const text = preloader?.querySelector('.wolf-preloader-text');

            preloader?.style.setProperty('--preloader-progress', String(value));

            if(text){
                text.textContent = `${Math.round(value * 100)}%`;
            }
        },

        waitForInitial(scope){
            const pageLoaded = document.readyState === 'complete'
                ? Promise.resolve()
                : new Promise(resolve => window.addEventListener('load', resolve, {once:true}));

            const fontsLoaded = document.fonts?.ready || Promise.resolve();

            return Promise.all([
                pageLoaded,
                fontsLoaded,
                this.waitForMedia(scope)
            ]);
        },

        waitForMedia(scope){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;
            MediaFallback.prepare(root);
            const media = Array.from(root.querySelectorAll('img, video'));

            if(!media.length){
                this.setProgress(1);
                return Promise.resolve();
            }

            let loaded = 0;
            const markLoaded = () => {
                loaded += 1;
                this.setProgress(loaded / media.length);
            };

            const mediaPromises = media.map(element => {
                if(element instanceof HTMLImageElement){
                    return this.waitForImage(element).then(markLoaded);
                }

                return this.waitForVideo(element).then(markLoaded);
            });

            return Promise.race([
                Promise.all(mediaPromises),
                new Promise(resolve => window.setTimeout(resolve, this.timeout))
            ]);
        },

        waitForImage(image){
            if(image.complete && image.naturalWidth > 0){
                return Promise.resolve();
            }

            return new Promise(resolve => {
                image.addEventListener('load', resolve, {once:true});
                image.addEventListener('error', () => {
                    const fallbackPending = MediaFallback.useImage(image)
                        || image.dataset.fallbackApplied === 'true';

                    if(!fallbackPending){
                        resolve();
                        return;
                    }

                    image.addEventListener('load', resolve, {once:true});
                    image.addEventListener('error', resolve, {once:true});
                }, {once:true});

                if(image.complete && image.naturalWidth === 0){
                    MediaFallback.useImage(image);
                }
            });
        },

        waitForVideo(video){
            if(video.readyState >= HTMLMediaElement.HAVE_FUTURE_DATA){
                return Promise.resolve();
            }

            video.preload = 'auto';
            video.load();

            return new Promise(resolve => {
                video.addEventListener('canplay', resolve, {once:true});
                video.addEventListener('error', () => {
                    const fallbackPending = MediaFallback.useVideo(video)
                        || video.dataset.fallbackApplied === 'true';

                    if(!fallbackPending){
                        resolve();
                        return;
                    }

                    video.addEventListener('canplay', resolve, {once:true});
                    video.addEventListener('error', resolve, {once:true});
                }, {once:true});
            });
        }
    };

    const CookieConsent = {
        storageKey: 'carlvon-cookie-consent',

        init(){
            const savedConsent = localStorage.getItem(this.storageKey);

            if(savedConsent){
                this.apply(savedConsent, false);
                return;
            }

            document.documentElement.classList.add('cookieBlocked');
            this.loadContent();
        },

        async loadContent(){
            const target = document.querySelector('[data-cookie-content]');

            if(!target) return;

            const language = (document.documentElement.lang || 'de').split('-')[0];
            const formData = new FormData();
            formData.append('req', 'site');
            formData.append('site', 'admin/cookie');
            formData.append('lang', language);
            const response = await fetch(window.BACKBONE || '/_PARAMS.php', {
                method: 'POST',
                body: formData
            });

            if(response.ok){
                target.innerHTML = await response.text();
                target.querySelector('button')?.focus();
            }
        },

        apply(value, persist = true){
            if(persist){
                localStorage.setItem(this.storageKey, value);
            }

            document.documentElement.dataset.cookieConsent = value;
            document.documentElement.classList.remove('cookieBlocked');
            document.dispatchEvent(new CustomEvent('cookieconsentchange', {
                detail: {value}
            }));
        },

        reset(){
            localStorage.removeItem(this.storageKey);
            document.documentElement.classList.add('cookieBlocked');
            this.loadContent();
        }
    };

    function initCenterPics(scope = document){
        const root = scope instanceof Element || scope instanceof Document
            ? scope
            : document;
        const mobileQuery = window.matchMedia?.('(max-width: 800px)');

        const isMobile = () => Boolean(mobileQuery?.matches);
        const debounce = (fn, wait = 150) => {
            let timer = null;
            return () => {
                window.clearTimeout(timer);
                timer = window.setTimeout(fn, wait);
            };
        };

        root.querySelectorAll('.center_pic').forEach(section => {
            section.querySelectorAll(':scope > .inner-image').forEach(marker => {
                if(marker.dataset.centerPicReady === '1') return;

                const src = marker.dataset.imgSrc || marker.getAttribute('src') || marker.querySelector('img')?.getAttribute('src');
                if(!src) return;

                const position = (marker.dataset.imgPos || 'top_center').toLowerCase();
                const [vertical = 'top', horizontal = 'center'] = position.split('_');
                let img = marker.querySelector('img');

                marker.dataset.centerPicReady = '1';

                if(!img){
                    img = document.createElement('img');
                    marker.appendChild(img);
                }

                img.src = src;
                img.alt = marker.dataset.imgAlt || img.alt || '';
                img.decoding = 'async';

                const setupSide = widthPx => {
                    marker.classList.remove('inner-image--abs-center');
                    marker.style.width = `${widthPx}px`;
                    marker.style.position = 'static';

                    const column = marker.closest('.center_pic__col');
                    if(!column) return;

                    if(vertical === 'bottom'){
                        column.appendChild(marker);
                    } else {
                        column.insertBefore(marker, column.firstChild);
                    }
                };

                const alignBottomSpacers = (spacers, heightPx) => {
                    spacers.forEach(spacer => {
                        spacer.style.marginTop = '0px';
                    });

                    const sectionTop = section.getBoundingClientRect().top;
                    const bottoms = spacers.map(spacer => spacer.getBoundingClientRect().bottom - sectionTop);
                    const maxBottom = Math.max(...bottoms);

                    spacers.forEach((spacer, index) => {
                        const diff = maxBottom - bottoms[index];
                        if(diff > 0) spacer.style.marginTop = `${diff}px`;
                    });

                    marker.style.top = `${maxBottom - heightPx}px`;
                    marker.style.bottom = '';
                };

                const setupCenter = (widthPx, heightPx) => {
                    marker.classList.add('inner-image--abs-center');
                    marker.style.position = '';
                    marker.style.width = `${widthPx}px`;

                    const columns = Array.from(section.querySelectorAll(':scope .center_pic__col'));
                    if(columns.length < 2) return;

                    const spacers = columns.slice(0, 2).map((column, index) => {
                        let spacer = column.querySelector(':scope > .center_pic__spacer');

                        if(!spacer){
                            spacer = document.createElement('div');
                        }

                        spacer.className = `center_pic__spacer ${index === 0 ? 'center_pic__spacer--left' : 'center_pic__spacer--right'}`;
                        spacer.style.width = `${widthPx / 2}px`;
                        spacer.style.height = `${heightPx}px`;
                        spacer.style.marginTop = '0px';

                        if(vertical === 'bottom'){
                            column.appendChild(spacer);
                        } else {
                            column.insertBefore(spacer, column.firstChild);
                        }

                        return spacer;
                    });

                    if(vertical === 'bottom'){
                        alignBottomSpacers(spacers, heightPx);
                    } else {
                        marker.style.top = `${spacers[0].getBoundingClientRect().top - section.getBoundingClientRect().top}px`;
                        marker.style.bottom = '';
                    }
                };

                const build = () => {
                    section.querySelectorAll(':scope .center_pic__spacer').forEach(spacer => spacer.remove());

                    marker.classList.remove('inner-image--abs-center');
                    marker.style.top = '';
                    marker.style.bottom = '';

                    const requestedSize = marker.dataset.imgSize || '300px';
                    const widthPx = Math.min(parseFloat(requestedSize) || 300, section.clientWidth || 300);
                    const ratio = img.naturalWidth > 0 ? img.naturalHeight / img.naturalWidth : 1;
                    const heightPx = Math.round(widthPx * ratio);

                    if(isMobile()){
                        marker.style.width = `${widthPx}px`;
                        return;
                    }

                    if(horizontal === 'center'){
                        setupCenter(widthPx, heightPx);
                    } else {
                        setupSide(widthPx);
                    }
                };

                img.addEventListener('load', build);
                if(img.complete && img.naturalWidth) build();
                window.addEventListener('resize', debounce(build));
            });
        });
    }

    function initNavSections(scope = document){
        const root = scope instanceof Element || scope instanceof Document
            ? scope
            : document;
        const navSections = [
            ...(root.matches?.('.navSection') ? [root] : []),
            ...Array.from(root.querySelectorAll('.navSection'))
        ];

        if(!navSections.length) return;

        const slugify = value => String(value || 'section')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '') || 'section';

        const getHeaderOffset = () => {
            const header = document.querySelector('header, #header');
            return (header?.getBoundingClientRect().height || 72) + 32;
        };

            const scrollToTarget = target => {
                const top = target.getBoundingClientRect().top + window.scrollY - getHeaderOffset();

                window.scrollTo({
                top: Math.max(0, top),
                behavior: 'smooth'
                });
            };

        const activateTarget = target => {
            if(!target) return;

            scrollToTarget(target.element);
            history.replaceState(history.state, '', `#${target.element.id}`);
        };

        const states = [];

        navSections.forEach(navSection => {
            const main = navSection.querySelector(':scope > main');
            if(!main) return;

            const slugCounts = new Map();
            const uniqueId = text => {
                const base = slugify(text);
                const next = (slugCounts.get(base) || 0) + 1;
                slugCounts.set(base, next);

                return next === 1 ? base : `${base}-${next}`;
            };

            let aside = navSection.querySelector(':scope > aside');
            if(!aside){
                aside = document.createElement('aside');
                navSection.insertBefore(aside, main);
            }

            let nav = aside.querySelector(':scope > nav');
            if(!nav){
                nav = document.createElement('nav');
                aside.appendChild(nav);
            }

            nav.textContent = '';
            nav.dataset.generated = 'navSection';

            const targets = [];
            const groups = [];

            Array.from(main.querySelectorAll(':scope > .mainNav')).forEach(mainItem => {
                const heading = mainItem.querySelector(':scope > h2');
                const label = heading?.textContent?.trim();
                if(!heading || !label) return;

                if(!mainItem.id){
                    mainItem.id = uniqueId(label);
                }

                const subItems = Array.from(mainItem.querySelectorAll(':scope > .subNav'));
                const group = document.createElement('div');
                const link = document.createElement('a');

                group.className = subItems.length ? 'navSection__group open' : 'navSection__group';
                link.className = 'navSection__link navSection__link--main';
                link.href = `#${mainItem.id}`;
                link.textContent = label;

                group.appendChild(link);

                nav.appendChild(group);
                targets.push({element: mainItem, heading, link, group});
                groups.push(group);

                if(subItems.length){
                    const subList = document.createElement('div');
                    subList.className = 'navSection__subNav';
                    group.appendChild(subList);

                    subItems.forEach(subItem => {
                        const subHeading = subItem.querySelector(':scope > h2');
                        const subLabel = subHeading?.textContent?.trim();
                        if(!subHeading || !subLabel) return;

                        if(!subItem.id){
                            subItem.id = uniqueId(subLabel);
                        }

                        const subLink = document.createElement('a');
                        subLink.className = 'navSection__link navSection__link--sub';
                        subLink.href = `#${subItem.id}`;
                        subLink.textContent = subLabel;
                        subList.appendChild(subLink);
                        targets.push({element: subItem, heading: subHeading, link: subLink, group});
                    });
                }
            });

            if(!targets.length) return;

            targets.forEach(({element, link}) => {
                link.addEventListener('click', event => {
                    event.preventDefault();
                    activateTarget({element});
                    updateActive();
                });
            });

            const updateActive = () => {
                const offset = getHeaderOffset() + 36;
                let active = targets[0];

                targets.forEach(target => {
                    if(target.element.getBoundingClientRect().top <= offset){
                        active = target;
                    }
                });

                targets.forEach(target => {
                    target.link.classList.toggle('active', target === active);
                });

                groups.forEach(group => {
                    group.classList.toggle('active', group === active.group);
                });
            };

            states.push(updateActive);
            updateActive();
        });

        if(!states.length) return;

        window.removeEventListener('scroll', window.__siteNavSectionScroll);
        window.removeEventListener('resize', window.__siteNavSectionScroll);
        window.__siteNavSectionScroll = () => window.requestAnimationFrame(() => {
            states.forEach(update => update());
        });
        window.addEventListener('scroll', window.__siteNavSectionScroll, {passive:true});
        window.addEventListener('resize', window.__siteNavSectionScroll);
    }

    function initInnerImages(scope = document){
        const root = scope instanceof Element || scope instanceof Document
            ? scope
            : document;

        const positionMap = {
            center: 'top_center',
            top: 'top_center',
            left: 'top_left',
            right: 'top_right',
            bottom: 'bottom_center'
        };

        const getWeight = node => {
            if(node.nodeType === Node.TEXT_NODE) return node.textContent.trim().length;
            if(node.nodeType !== Node.ELEMENT_NODE) return 0;
            const tag = node.tagName.toLowerCase();
            const headingBoost = /^h[1-6]$/.test(tag) ? 80 : 0;
            return Math.max(20, node.textContent.trim().length + headingBoost);
        };

        const splitLongParagraph = node => {
            if(node.nodeType !== Node.ELEMENT_NODE || node.tagName.toLowerCase() !== 'p') return [node];
            if(node.children.length > 0) return [node];

            const text = node.textContent.trim();
            if(text.length < 420) return [node];

            const sentences = text.match(/[^.!?]+[.!?]+(?:\s+|$)|[^.!?]+$/g) || [text];
            const chunks = [];
            let current = '';

            sentences.forEach(sentence => {
                const cleanSentence = sentence.trim();
                if(!cleanSentence) return;

                if(current && `${current} ${cleanSentence}`.length > 320){
                    chunks.push(current);
                    current = cleanSentence;
                } else {
                    current = current ? `${current} ${cleanSentence}` : cleanSentence;
                }
            });

            if(current) chunks.push(current);

            return chunks.map(chunk => {
                const paragraph = node.cloneNode(false);
                paragraph.textContent = chunk;
                return paragraph;
            });
        };

        const normalizeFlowNodes = nodes => nodes.flatMap(node => splitLongParagraph(node));

        const distributeNodes = (nodes, columnCount) => {
            const weights = nodes.map(getWeight);
            const total = weights.reduce((sum, weight) => sum + weight, 0);
            const target = Math.max(1, total / columnCount);
            const columns = Array.from({length: columnCount}, () => []);
            let columnIndex = 0;
            let currentWeight = 0;

            nodes.forEach((node, index) => {
                const remainingNodes = nodes.length - index;
                const remainingColumns = columnCount - columnIndex;
                const weight = weights[index];

                if(
                    columnIndex < columnCount - 1
                    && currentWeight > 0
                    && currentWeight + weight > target
                    && columns[columnIndex].length > 1
                    && remainingNodes >= remainingColumns
                ){
                    columnIndex += 1;
                    currentWeight = 0;
                }

                columns[columnIndex].push(node);
                currentWeight += weight;
            });

            return columns;
        };

        const getColumnContentNodes = column => Array.from(column.childNodes).filter(node => {
            if(node.nodeType === Node.TEXT_NODE) return node.textContent.trim().length > 0;
            return !(node.nodeType === Node.ELEMENT_NODE && node.classList.contains('inner-image-spacer'));
        });

        const getColumnHeights = columns => columns.map(column => column.getBoundingClientRect().height);

        const getHeightSpread = columns => {
            const heights = getColumnHeights(columns);
            return Math.max(...heights) - Math.min(...heights);
        };

        const balanceColumns = wrap => {
            if(window.matchMedia?.('(max-width: 700px)').matches) return;
            if(wrap.classList.contains('center_pix--center-image')) return;

            const columns = Array.from(wrap.querySelectorAll(':scope > .center_pix__col'));
            if(columns.length < 2) return;

            for(let iteration = 0; iteration < 18; iteration += 1){
                let bestMove = null;
                let bestSpread = getHeightSpread(columns);

                columns.forEach((column, index) => {
                    const nextColumn = columns[index + 1];
                    if(!nextColumn) return;

                    const leftNodes = getColumnContentNodes(column);
                    const rightNodes = getColumnContentNodes(nextColumn);

                    [
                        {
                            node: leftNodes[leftNodes.length - 1],
                            from: column,
                            to: nextColumn,
                            insert: () => nextColumn.insertBefore(leftNodes[leftNodes.length - 1], rightNodes[0] || null),
                            revert: node => column.appendChild(node)
                        },
                        {
                            node: rightNodes[0],
                            from: nextColumn,
                            to: column,
                            insert: () => column.appendChild(rightNodes[0]),
                            revert: node => nextColumn.insertBefore(node, rightNodes[1] || null)
                        }
                    ].forEach(candidate => {
                        if(!candidate.node || getColumnContentNodes(candidate.from).length <= 1) return;

                        const node = candidate.node;
                        candidate.insert();
                        const spread = getHeightSpread(columns);
                        candidate.revert(node);

                        if(spread + 8 < bestSpread){
                            bestSpread = spread;
                            bestMove = candidate;
                        }
                    });
                });

                if(!bestMove) break;
                bestMove.insert();
            }
        };

        root.querySelectorAll('.center_pix').forEach(wrap => {
            if(wrap.dataset.innerImageReady === '1') return;

            const legacyMarker = wrap.querySelector(':scope > .inner-image');
            const src = wrap.dataset.imgSrc || legacyMarker?.dataset.imgSrc || legacyMarker?.getAttribute('src') || legacyMarker?.querySelector('img')?.getAttribute('src');
            if(!src) return;

            const columnCount = Math.max(1, parseInt(wrap.dataset.cols || '2', 10) || 2);
            const sourceNodes = [];

            Array.from(wrap.childNodes).forEach(node => {
                if(node === legacyMarker) return;

                if(node.nodeType === Node.TEXT_NODE && node.textContent.trim().length === 0) return;

                if(node.nodeType === Node.ELEMENT_NODE && node.classList.contains('col')){
                    sourceNodes.push(...Array.from(node.childNodes).filter(child => child.nodeType !== Node.TEXT_NODE || child.textContent.trim().length > 0));
                    return;
                }

                if(node.nodeType === Node.ELEMENT_NODE && node.classList.contains('columnize-2')){
                    sourceNodes.push(...Array.from(node.childNodes).filter(child => child.nodeType !== Node.TEXT_NODE || child.textContent.trim().length > 0));
                    return;
                }

                sourceNodes.push(node);
            });

            const flowNodes = normalizeFlowNodes(sourceNodes);

            wrap.textContent = '';
            wrap.dataset.innerImageReady = '1';
            wrap.dataset.innerImageCols = String(columnCount);
            wrap.classList.add('inner-image-wrap');

            const marker = document.createElement('div');
            marker.className = 'inner-image';
            marker.dataset.imgSrc = src;
            marker.dataset.imgPos = wrap.dataset.imgPos || legacyMarker?.dataset.imgPos || 'top_center';
            marker.dataset.imgSize = wrap.dataset.imgSize || legacyMarker?.dataset.imgSize || '300px';
            marker.dataset.imgAlt = wrap.dataset.imgAlt || legacyMarker?.dataset.imgAlt || '';
            if(marker.dataset.imgPos === 'center') marker.dataset.imgPos = 'top_center';
            if(marker.dataset.imgPos.endsWith('_center')) wrap.classList.add('center_pix--center-image');
            wrap.appendChild(marker);

            const distributedColumns = distributeNodes(flowNodes, columnCount);
            distributedColumns.forEach(columnNodes => {
                const column = document.createElement('div');
                column.className = 'center_pix__col col';
                column.dataset.centerPixColumn = String(wrap.querySelectorAll(':scope > .center_pix__col').length);
                columnNodes.forEach(node => column.appendChild(node));
                wrap.appendChild(column);
            });

            requestAnimationFrame(() => balanceColumns(wrap));
        });

        root.querySelectorAll('.float_pix').forEach(wrap => {
            if(wrap.dataset.floatPixReady === '1') return;

            const legacyImage = wrap.querySelector(':scope > .float_pix__image');
            const src = wrap.dataset.imgSrc || legacyImage?.dataset.imgSrc || legacyImage?.getAttribute('src') || legacyImage?.querySelector('img')?.getAttribute('src');
            if(!src) return;

            const position = (wrap.dataset.imgPos || legacyImage?.dataset.imgPos || 'left').toLowerCase() === 'right'
                ? 'right'
                : 'left';
            const imageWrap = document.createElement('span');
            const img = document.createElement('img');
            const paragraphs = Array.from(wrap.querySelectorAll(':scope > p'));
            const anchor = paragraphs[1] || paragraphs[0] || wrap.firstChild;

            legacyImage?.remove();
            wrap.dataset.floatPixReady = '1';
            wrap.dataset.imgPos = position;
            wrap.style.setProperty('--float-pix-size', wrap.dataset.imgSize || '320px');
            wrap.classList.toggle('float_pix--right', position === 'right');
            wrap.classList.toggle('float_pix--left', position !== 'right');
            imageWrap.className = 'float_pix__image';
            imageWrap.dataset.imgPos = position;
            img.src = src;
            img.alt = wrap.dataset.imgAlt || legacyImage?.dataset.imgAlt || '';
            img.decoding = 'async';
            imageWrap.appendChild(img);

            if(anchor){
                wrap.insertBefore(imageWrap, anchor);
            } else {
                wrap.appendChild(imageWrap);
            }
        });

        root.querySelectorAll('.inner-image').forEach(marker => {
            if(marker.closest('.center_pic')) return;
            if(marker.dataset.innerImageReady === '1') return;

            const src = marker.dataset.imgSrc || marker.getAttribute('src') || marker.querySelector('img')?.getAttribute('src');
            if(!src) return;

            const wrap = marker.closest('.center_pix, .inner-image-wrap') || marker.parentElement;
            if(!wrap) return;

            marker.dataset.innerImageReady = '1';
            marker.classList.add('inner-image');
            wrap.classList.add('inner-image-wrap');

            const columnCount = Math.max(1, parseInt(wrap.dataset.innerImageCols || wrap.dataset.cols || '', 10) || wrap.querySelectorAll(':scope > .col').length || 1);
            wrap.dataset.innerImageCols = String(columnCount);

            let img = marker.querySelector('img');
            if(!img){
                img = document.createElement('img');
                marker.appendChild(img);
            }

            img.src = src;
            img.alt = marker.dataset.imgAlt || img.alt || '';
            img.decoding = 'async';

            const update = () => {
                const requestedSize = marker.dataset.imgSize || '300px';
                const requestedPosition = marker.dataset.imgPos || 'top_center';
                const position = positionMap[requestedPosition] || requestedPosition;
                const width = Math.min(parseFloat(requestedSize) || 300, wrap.clientWidth || 300);
                const align = position.endsWith('_left') ? 'left' : position.endsWith('_right') ? 'right' : 'center';
                const centerImage = position.endsWith('_center');
                const positionClass = `inner-image--${position.replace('_', '-')}`;
                const imageWidth = Math.max(1, Math.round(width));
                const columnGap = parseFloat(getComputedStyle(wrap).columnGap) || 0;
                const imageHeight = img.naturalWidth > 0
                    ? Math.max(1, Math.round((img.naturalHeight / img.naturalWidth) * imageWidth))
                    : imageWidth;
                const spacerWidth = centerImage
                    ? Math.max(1, Math.round(((imageWidth - columnGap) / 2) + 18))
                    : align === 'center'
                    ? Math.max(1, Math.round(imageWidth / Math.max(1, columnCount)))
                    : imageWidth;

                marker.classList.forEach(className => {
                    if(className.startsWith('inner-image--')) marker.classList.remove(className);
                });
                marker.classList.add(positionClass);
                wrap.classList.toggle('center_pix--center-image', centerImage);
                wrap.classList.toggle('center_pix--top-center', centerImage && position === 'top_center');
                wrap.classList.toggle('center_pix--bottom-center', centerImage && position === 'bottom_center');
                marker.style.width = centerImage ? `${imageWidth}px` : '';
                img.setAttribute('width', String(imageWidth));

                wrap.querySelectorAll(':scope > .col').forEach((column, index) => {
                    let spacer = column.querySelector(':scope > .inner-image-spacer');
                    if(!spacer){
                        spacer = document.createElement('img');
                        spacer.className = 'inner-image-spacer';
                        spacer.alt = '';
                        spacer.decoding = 'async';
                        spacer.setAttribute('aria-hidden', 'true');
                        column.prepend(spacer);
                    }

                    const mobileStack = window.matchMedia?.('(max-width: 700px)').matches;
                    const useSpacer = !mobileStack && (centerImage
                        ? columnCount > 1 && (index === 0 || index === columnCount - 1)
                        : (
                            (align === 'left' && index === 0)
                            || (align === 'right' && index === columnCount - 1)
                        ));
                    const floatLeft = align === 'left' || (align === 'center' && index > 0);

                    spacer.classList.toggle('inner-image-spacer--left', floatLeft);
                    spacer.classList.toggle('inner-image-spacer--right', !floatLeft);
                    spacer.classList.toggle('inner-image-spacer--inactive', !useSpacer);
                    spacer.src = src;
                    spacer.setAttribute('width', String(spacerWidth));
                    spacer.setAttribute('height', String(centerImage ? imageHeight : spacerWidth));
                    spacer.style.width = `${spacerWidth}px`;
                    spacer.style.height = centerImage ? `${imageHeight}px` : '';
                });

                requestAnimationFrame(() => balanceColumns(wrap));
            };

            img.addEventListener('load', update, {once:true});
            update();

            if(window.ResizeObserver){
                const observer = new ResizeObserver(update);
                observer.observe(wrap);
                marker._innerImageObserver = observer;
            } else {
                window.addEventListener('resize', update);
            }
        });
    }

    const Site = {

        init(){
            const urlParameters = new URLSearchParams(window.location.search);
            const cookieValues = Object.fromEntries(
                document.cookie
                    .split(';')
                    .map(cookie => cookie.trim().split('=').map(decodeURIComponent))
                    .filter(parts => parts.length === 2)
            );
            const requestedLanguage = urlParameters.get('lang') || cookieValues.lCode;
            const requestedDirection = urlParameters.get('dir') || cookieValues.lDirection || 'ltr';
            const requestedSite = urlParameters.get('site');
            const requestedLand = urlParameters.get('land')
                || document.documentElement.getAttribute('land')
                || cookieValues.cCode
                || 'US';

            if(requestedLanguage){
                document.cookie = `lCode=${encodeURIComponent(requestedLanguage)}; path=/; max-age=31536000; SameSite=Lax`;
                document.documentElement.lang = requestedLanguage;
                document.documentElement.dir = requestedDirection;
                document.documentElement.setAttribute('land', requestedLand.toUpperCase());

                const codeLabel = document.querySelector('.languageMenu__code');

                if(codeLabel){
                    codeLabel.textContent = requestedLanguage.split('-')[0].toUpperCase();
                }
            }

            MediaFallback.init();
            CookieConsent.init();
            this.bindLinks();
            this.bindPopstate();
            this.initMainMenu();
            this.initPage();
            const initialSite = requestedSite || sessionStorage.getItem('initialSite');

            if(initialSite){
                sessionStorage.removeItem('initialSite');
                document.documentElement.dataset.site = initialSite;
                this.load(initialSite, false);
                return;
            }

            history.replaceState(
                {site:document.documentElement.dataset.site || 'home'},
                '',
                this.getRootUrl()
            );
            this.load('home', false);
        },

        initMainMenu(){
            const toggle = document.querySelector('.menuToggle');
            const header = document.querySelector('.siteHeader');
            const menu = document.querySelector('.siteMenu');
            const mainMenu = menu?.querySelector('.mainMenu');
            const languageItem = document.querySelector('.mainMenu__item--languages');
            const backdrop = document.querySelector('.menuBackdrop');
            const scrollTopButton = document.querySelector('.scrollTopButton');

            if(!toggle || !menu) return;

            const mobileMenu = this.ensureMobileMenu(menu);
            const menuItems = Array.from(mainMenu?.children || []);
            const bigItems = Array.from(document.querySelectorAll('.siteMenu .mainMenu__item--big'));
            const mobileBigItems = Array.from(mobileMenu?.querySelectorAll('.mainMenu__item--big') || []);

            const moveIndicator = item => {
                if(!mainMenu || !item || window.innerWidth <= 900) return;

                const menuRect = mainMenu.getBoundingClientRect();
                const target = item.querySelector(':scope > a, :scope > button');

                if(!target) return;

                const targetRect = target.getBoundingClientRect();
                const targetStyle = window.getComputedStyle(target);
                const lineHeight = Number.parseFloat(targetStyle.lineHeight)
                    || Number.parseFloat(targetStyle.fontSize) * 1.2;
                const textWidth = Math.min(target.scrollWidth || targetRect.width, targetRect.width);
                const indicatorPadding = 14;
                const indicatorWidth = textWidth + indicatorPadding;
                const indicatorX = targetRect.left - menuRect.left + ((targetRect.width - indicatorWidth) / 2);

                mainMenu.style.setProperty(
                    '--menu-indicator-x',
                    `${indicatorX}px`
                );
                mainMenu.style.setProperty(
                    '--menu-indicator-width',
                    `${indicatorWidth}px`
                );
                mainMenu.style.setProperty(
                    '--menu-indicator-height',
                    `${lineHeight + 8}px`
                );
                mainMenu.style.setProperty('--menu-indicator-opacity', '1');
            };

            const persistentItem = () => {
                return mainMenu?.querySelector(':scope > li.opened')
                    || mainMenu?.querySelector(':scope > li:has(> a.current-link)');
            };

            const restoreIndicator = () => {
                menuItems.forEach(item => item.classList.remove('hovered'));

                const item = persistentItem();

                if(item){
                    moveIndicator(item);
                } else {
                    mainMenu?.style.setProperty('--menu-indicator-opacity', '0');
                }
            };

            menuItems.forEach(item => {
                item.addEventListener('pointerenter', () => {
                    menuItems.forEach(entry => entry.classList.toggle('hovered', entry === item));
                    moveIndicator(item);
                });

                item.addEventListener('focusin', () => {
                    menuItems.forEach(entry => entry.classList.toggle('hovered', entry === item));
                    moveIndicator(item);
                });
            });

            mainMenu?.addEventListener('pointerleave', restoreIndicator);
            mainMenu?.addEventListener('focusout', event => {
                if(!mainMenu.contains(event.relatedTarget)){
                    restoreIndicator();
                }
            });

            const closeMenus = () => {
                document.body.classList.remove('menu-open', 'big-menu-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('wolf-active');
                mobileMenu?.classList.remove('is-open');
                bigItems.forEach(item => {
                    item.classList.remove('is-open', 'opened');
                    item.querySelector(':scope > .mainMenu__trigger')?.setAttribute('aria-expanded', 'false');
                });
                mobileBigItems.forEach(item => {
                    item.classList.remove('is-open', 'opened');
                    item.querySelector(':scope > .mainMenu__trigger')?.setAttribute('aria-expanded', 'false');
                });
                languageItem?.classList.remove('is-open');
                languageItem?.classList.remove('opened');
                restoreIndicator();
            };

            toggle.addEventListener('click', () => {
                const willOpen = !document.body.classList.contains('menu-open');
                closeMenus();
                document.body.classList.toggle('menu-open', willOpen);
                mobileMenu?.classList.toggle('is-open', willOpen);
                toggle.setAttribute('aria-expanded', String(willOpen));
                toggle.classList.toggle('wolf-active', willOpen);
            });

            bigItems.forEach(bigItem => {
                const bigTrigger = bigItem.querySelector(':scope > .mainMenu__trigger');

                bigTrigger?.addEventListener('click', () => {
                    const willOpen = !bigItem.classList.contains('is-open');
                    closeMenus();
                    bigItem.classList.toggle('is-open', willOpen);
                    bigItem.classList.toggle('opened', willOpen);
                    bigTrigger.setAttribute('aria-expanded', String(willOpen));
                    document.body.classList.toggle('big-menu-open', willOpen);

                    if(willOpen){
                        moveIndicator(bigItem);
                    }
                });
            });

            mobileBigItems.forEach(mobileBigItem => {
                const mobileBigTrigger = mobileBigItem.querySelector(':scope > .mainMenu__trigger');

                mobileBigTrigger?.addEventListener('click', () => {
                    const willOpen = !mobileBigItem.classList.contains('is-open');

                    mobileBigItems.forEach(item => {
                        if(item === mobileBigItem) return;

                        item.classList.remove('is-open', 'opened');
                        item.querySelector(':scope > .mainMenu__trigger')?.setAttribute('aria-expanded', 'false');
                    });

                    mobileBigItem.classList.toggle('is-open', willOpen);
                    mobileBigItem.classList.toggle('opened', willOpen);
                    mobileBigTrigger.setAttribute('aria-expanded', String(willOpen));
                });
            });

            backdrop?.addEventListener('click', closeMenus);

            document.addEventListener('click', event => {
                if(!document.body.classList.contains('big-menu-open')) return;
                if(event.target.closest('.bigMenu, .mainMenu__trigger')) return;

                closeMenus();
            });

            document.addEventListener('keydown', event => {
                if(event.key === 'Escape') closeMenus();
            });

            menu.addEventListener('click', event => {
                if(event.target.closest('a')) closeMenus();
            });

            mobileMenu?.addEventListener('click', event => {
                const link = event.target.closest('a');

                if(!link) return;

                if(link.dataset.mobileMenuDelayComplete === '1'){
                    delete link.dataset.mobileMenuDelayComplete;
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();
                closeMenus();

                window.setTimeout(() => {
                    link.dataset.mobileMenuDelayComplete = '1';
                    link.click();
                }, 300);
            }, true);

            window.addEventListener('resize', () => {
                if(window.innerWidth > 900) {
                    document.body.classList.remove('menu-open');
                    mobileMenu?.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.classList.remove('wolf-active');
                    restoreIndicator();
                } else {
                    document.body.classList.remove('big-menu-open');
                    bigItems.forEach(item => {
                        item.classList.remove('is-open', 'opened');
                        item.querySelector(':scope > .mainMenu__trigger')?.setAttribute('aria-expanded', 'false');
                    });
                    mobileBigItems.forEach(item => {
                        item.classList.remove('is-open', 'opened');
                        item.querySelector(':scope > .mainMenu__trigger')?.setAttribute('aria-expanded', 'false');
                    });
                    languageItem?.classList.remove('is-open');
                    languageItem?.classList.remove('opened');
                }
            });

            const updateScrollState = () => {
                const isStucked = window.scrollY > 10;
                header?.classList.toggle('is-stucked', isStucked);
                scrollTopButton?.classList.toggle('is-visible', window.scrollY >= window.innerHeight);
            };

            scrollTopButton?.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            window.addEventListener('scroll', updateScrollState, {passive:true});
            updateScrollState();
            restoreIndicator();
        },

        ensureMobileMenu(menu){
            let mobileMenu = document.querySelector('.mobileMenu');

            if(mobileMenu) return mobileMenu;

            const clone = menu.cloneNode(true);
            clone.removeAttribute('id');
            clone.classList.remove('siteMenu');
            clone.classList.add('mobileMenu');
            clone.setAttribute('aria-label', 'Mobile Hauptnavigation');
            clone.querySelectorAll('[id]').forEach(element => element.removeAttribute('id'));
            clone.querySelectorAll('[data-ajax-bound]').forEach(element => {
                delete element.dataset.ajaxBound;
            });
            clone.querySelectorAll('.current-link, .hovered, .opened, .is-open').forEach(element => {
                element.classList.remove('current-link', 'hovered', 'opened', 'is-open');
            });
            clone.querySelectorAll('[aria-expanded]').forEach(element => {
                element.setAttribute('aria-expanded', 'false');
            });

            document.body.appendChild(clone);
            this.bindLinks(clone);

            return clone;
        },

        async changeLanguage(language, direction = 'ltr'){
            if(!language) return;

            const formData = new FormData();
            formData.append('lang', language);
            formData.append('dir', direction);
            formData.append('ajax', '1');

            Preloader.show();

            try {
                const response = await fetch('/admin/set-language.php', {
                    method: 'POST',
                    body: formData
                });

                if(!response.ok){
                    throw new Error('Language could not be saved');
                }

                document.documentElement.lang = language;
                document.documentElement.dir = direction;

                const codeLabel = document.querySelector('.languageMenu__code');

                if(codeLabel){
                    codeLabel.textContent = language.split('-')[0].toUpperCase();
                }

                await this.load('admin/languages', false);
            } catch {
                Preloader.hide();
                window.location.assign(
                    `/admin/set-language.php?lang=${encodeURIComponent(language)}&dir=${encodeURIComponent(direction)}`
                );
            }
        },

        bindLinks(scope = document){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;

            root.querySelectorAll('[data-link]').forEach(link => {
                if(link.dataset.ajaxBound === '1') return;

                link.dataset.ajaxBound = '1';
                link.addEventListener('click', event => {
                    event.preventDefault();

                    const site = link.getAttribute('data-link');

                    if(!site) return;

                    this.load(site, true, link);
                });
            });

            if(this.globalActionsBound) return;

            this.globalActionsBound = true;
            document.addEventListener('click', event => {
                const consentButton = event.target.closest('[data-cookie-consent]');

                if(!consentButton) return;

                event.preventDefault();
                CookieConsent.apply(consentButton.getAttribute('data-cookie-consent'));
            });
        },

        bindPopstate(){
            window.addEventListener('popstate', e => {
                const site = e.state?.site || document.documentElement.dataset.site || 'site/home';
                this.load(site, false);
            });
        },

        async load(site, addToHistory = true, trigger = null){

            const middle = document.querySelector('#middle');
            const lang = document.documentElement.getAttribute('lang') || 'de';
            const land = document.documentElement.getAttribute('land') || 'US';

            if(!middle || !site) return;

            document.documentElement.dataset.site = site;
            Preloader.show();
            middle.classList.add('isLoading');

            const formData = new FormData();
            formData.append('req', 'site');
            formData.append('site', site);
            formData.append('lang', lang);
            formData.append('land', land);

            try {
                const response = await fetch(window.BACKBONE || '/_PARAMS.php', {
                    method: 'POST',
                    body: formData
                });
                const html = await response.text();

                if(!html.trim()){
                    throw new Error(`Empty response with ${response.status}`);
                }

                middle.innerHTML = html;
                middle.dataset.status = String(response.status);
                this.bindLinks(middle);
                this.setCurrentLink(site, trigger);
                history.replaceState({site:site}, '', this.getRootUrl());

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                await Preloader.waitForMedia(middle);
                this.initPage(middle);
                middle.classList.remove('isLoading');
                Preloader.hide();
            } catch {
                middle.innerHTML = '<section class="content"><h1>Fehler</h1><p>Inhalt konnte nicht geladen werden.</p></section>';
                middle.classList.remove('isLoading');
                this.initPage(middle);
                Preloader.hide();
            }
        },

        getRootUrl(){
            return window.APP_ROOT || '/';
        },

        setCurrentLink(site, trigger = null){
            document.querySelectorAll('[data-link]').forEach(a => {
                const isCurrent = trigger
                    ? a === trigger
                    : a.getAttribute('data-link') === site;

                a.classList.toggle('current-link', isCurrent);
            });
        },

        initPage(scope = document){
            this.initFaqGroups(scope);
            initNavSections(scope);
            initCenterPics(scope);
            initInnerImages(scope);
            this.initLegalDocs(scope);
            this.initTicketing(scope);
            initScrollBehaviour(scope);
        },

        async initTicketing(scope = document){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;

            const ticketRoot = root.querySelector('#mid, .ticket-table, .ticket-control, .ticketForms');

            if(!ticketRoot) return;

            try {
                await this.ensureTicketingRuntime(root);
            } catch (error) {
                console.error(error);
                return;
            }

            if(window.Tick){
                window.Tick.loc = '/admin/ticketing/';
            }

            this.bindTicketingButtons(root);
        },

        async ensureTicketingRuntime(scope = document){
            if(!window.jQuery){
                await loadScriptOnce('https://code.jquery.com/jquery-3.7.1.min.js', 'jquery');
            }

            window.$ = window.jQuery;
            window.frame = window.frame || '/admin/';
            window.BACKBONE = window.BACKBONE || '/_PARAMS.php';
            window.AX = window.AX || {
                site(site){
                    if(window.APP?.load){
                        return window.APP.load(site === 'about/pricing' ? 'admin/ticketing' : site);
                    }
                    window.location.href = '/';
                    return null;
                }
            };

            const $ = window.jQuery;
            $.WOLF = $.WOLF || {};
            $.WOLF.reinit = $.WOLF.reinit || (() => window.APP?.initPage(document.querySelector('#middle') || document));
            $.WOLF.log = $.WOLF.log || {};
            $.WOLF.log.button = $.WOLF.log.button || ((button, active) => {
                if(button) $(button).toggleClass('isLoading', Boolean(active));
            });

            if(!$.fn.hasAttr){
                $.fn.hasAttr = function(name){
                    return this.attr(name) !== undefined;
                };
            }

            if(!$.fn.fadingIn){
                $.fn.fadingIn = function(duration){
                    return this.fadeIn(duration);
                };
            }

            window.Value = window.Value || {
                get(key){
                    return localStorage.getItem(key) || document.querySelector(`[name="${key}"]`)?.value || '';
                },
                set(key, value){
                    localStorage.setItem(key, value ?? '');
                }
            };

            window.TT = window.TT || ((key) => key);
            window.LL = window.LL || ((area, key) => key || area);
            window.clogs = window.clogs || ((...args) => console.log(...args));
            window.getRandom = window.getRandom || (() => `${Date.now()}${Math.floor(Math.random() * 10000)}`);
            window.check = window.check || ((response) => typeof response === 'string' ? JSON.parse(response) : response);
            window.testResponse = window.testResponse || window.check;
            window.normalizeKeys = window.normalizeKeys || ((keys) => Array.isArray(keys) ? keys : String(keys || '').split(',').map(key => key.trim()).filter(Boolean));
            window.validateEmail = window.validateEmail || ((email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email));
            window.validEmail = window.validEmail || window.validateEmail;
            window.validString = window.validString || ((value) => /^[A-Za-z0-9]{5,12}$/.test(value || ''));
            window.minString = window.minString || ((value, min = 5) => String(value || '').trim().length >= min);
            window.scrollStart = window.scrollStart || (() => window.scrollTo({top:0, behavior:'smooth'}));
            window.register = window.register || ((target) => window.APP?.initPage(target?.get?.(0) || target || document));
            window.standardInfo = window.standardInfo || ((message) => console.info(message));
            window.standardSuccess = window.standardSuccess || ((message) => console.info(message));
            window.standardError = window.standardError || ((message) => console.error(message));
            window.swupAlert = window.swupAlert || ((message, options = {}) => {
                console.info(message);
                if(typeof options.afterClose === 'function') options.afterClose();
            });
            window.swupError = window.swupError || ((message) => console.error(message));
            window.errorTip = window.errorTip || ((target, message) => {
                console.warn(message);
                target?.addClass?.('hasError');
                return false;
            });
            window.clearLocal = window.clearLocal || (() => {
                [
                    'selectedPrice',
                    'selectedQuantity',
                    'selectedSum',
                    'selectedType',
                    'selectedProduct',
                    'voucher',
                    'order_id',
                    'voucher_id'
                ].forEach(key => localStorage.removeItem(key));
            });

            const inertScripts = Array.from((scope instanceof Element ? scope : document).querySelectorAll('script[src]'));

            for(const script of inertScripts){
                const src = script.getAttribute('src');
                if(src && (src.includes('paypal.com/sdk') || src.includes('js.stripe.com'))){
                    await loadScriptOnce(src, src.includes('paypal.com') ? 'paypal-sdk' : 'stripe-sdk');
                }
            }

            await loadScriptOnce('/admin/ticketing/_login.js?v=20260617-1', 'ticket-login');
            window._Log = window._Log || $.WOLF.log;
            await loadScriptOnce('/admin/ticketing/_ticket.js?v=20260617-1', 'ticket-core');
            window.Tick = window.Tick || $.WOLF.ticket;
        },

        bindTicketingButtons(scope = document){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;

            root.querySelectorAll('.toggle-wrapper .toggle-option').forEach(option => {
                if(option.dataset.ticketToggleBound === '1') return;
                option.dataset.ticketToggleBound = '1';

                option.addEventListener('click', () => {
                    const wrapper = option.closest('.toggle-wrapper');
                    wrapper?.querySelectorAll('.toggle-option').forEach(item => item.classList.remove('active'));
                    option.classList.add('active');
                    localStorage.setItem('voucher', option.dataset.plan === 'voucher' ? 'true' : 'false');
                });
            });

            root.querySelectorAll('.buyTicket').forEach(button => {
                if(button.dataset.ticketBound === '1') return;
                button.dataset.ticketBound = '1';

                button.addEventListener('click', event => {
                    event.preventDefault();

                    const price = parseFloat(button.dataset.price || '0');
                    const type = button.dataset.type || 't';
                    const quantity = 1;
                    const activePlan = root.querySelector('.voucher-wrapper .toggle-option.active')?.dataset.plan;
                    const title = button.closest('.ticket-card')?.querySelector('.card-title')?.textContent?.trim() || 'Ticket';

                    localStorage.setItem('selectedPrice', String(price));
                    localStorage.setItem('selectedQuantity', String(quantity));
                    localStorage.setItem('selectedSum', String(price * quantity));
                    localStorage.setItem('selectedType', type);
                    localStorage.setItem('selectedProduct', title);
                    localStorage.setItem('voucher', activePlan === 'voucher' ? 'true' : 'false');
                    localStorage.setItem('lang', document.documentElement.getAttribute('lang') || 'de');
                    localStorage.setItem('land', document.documentElement.getAttribute('land') || 'AT');

                    if(window.Tick?.loadScreen){
                        window.Tick.loadScreen('1_ticketdata');
                    }
                });
            });
        },

        initFaqGroups(scope = document){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;

            const configurations = [
                {
                    group: 'faq-group',
                    item: 'faq-item',
                    heading: 'faq-heading',
                    trigger: 'faq-trigger',
                    panel: 'faq-panel'
                },
                {
                    group: 'acc-group',
                    item: 'acc-item',
                    heading: 'acc-heading',
                    trigger: 'acc-trigger',
                    panel: 'acc-panel'
                }
            ];

            configurations.forEach(config => {
                root.querySelectorAll(`.${config.group}`).forEach(group => {
                    const getPanel = targetTrigger => {
                        const nextPanel = targetTrigger.nextElementSibling?.classList.contains(config.panel)
                            ? targetTrigger.nextElementSibling
                            : null;

                        return nextPanel || targetTrigger.closest(`.${config.item}`)?.querySelector(`:scope > .${config.panel}`);
                    };

                    const setOpen = (targetItem, open) => {
                        const targetTrigger = targetItem.querySelector(
                            `:scope > .${config.trigger}, :scope > .${config.heading} > .${config.trigger}`
                        );
                        const targetPanel = targetTrigger
                            ? getPanel(targetTrigger)
                            : targetItem.querySelector(`:scope > .${config.panel}`);

                        targetItem.classList.toggle('open', open);
                        targetTrigger?.setAttribute('aria-expanded', String(open));
                        targetPanel?.setAttribute('aria-hidden', String(!open));
                    };

                    group.querySelectorAll(`.${config.trigger}`).forEach(trigger => {
                        const item = trigger.closest(`.${config.item}`);

                        if(!item || item.closest(`.${config.group}`) !== group) return;
                        if(trigger.dataset.disclosureBound === '1') return;
                        trigger.dataset.disclosureBound = '1';

                        if(trigger.tagName !== 'BUTTON'){
                            trigger.setAttribute('role', 'button');
                            trigger.setAttribute('tabindex', trigger.getAttribute('tabindex') || '0');
                        }

                        setOpen(item, item.classList.contains('open'));

                        const toggle = () => {
                            const willOpen = !item.classList.contains('open');

                            group.querySelectorAll(`:scope > .${config.item}`).forEach(groupItem => {
                                if(groupItem !== item) setOpen(groupItem, false);
                            });

                            setOpen(item, willOpen);
                        };

                        trigger.addEventListener('click', toggle);
                        trigger.addEventListener('keydown', event => {
                            if(trigger.tagName === 'BUTTON') return;
                            if(event.key !== 'Enter' && event.key !== ' ') return;

                            event.preventDefault();
                            toggle();
                        });
                    });
                });
            });

            root.querySelectorAll('.know_faq').forEach(group => {
                const headings = Array.from(group.children).filter(heading => (
                    heading.tagName === 'H4' && heading.nextElementSibling?.tagName === 'DIV'
                ));

                const setOpen = (heading, open) => {
                    const panel = heading.nextElementSibling;

                    heading.classList.toggle('open', open);
                    heading.setAttribute('aria-expanded', String(open));
                    panel?.setAttribute('aria-hidden', String(!open));
                };

                headings.forEach(heading => {
                    if(heading.dataset.disclosureBound === '1') return;
                    heading.dataset.disclosureBound = '1';
                    heading.setAttribute('role', 'button');
                    heading.setAttribute('tabindex', heading.getAttribute('tabindex') || '0');
                    setOpen(heading, heading.classList.contains('open'));

                    const toggle = () => {
                        const willOpen = !heading.classList.contains('open');

                        headings.forEach(groupHeading => {
                            if(groupHeading !== heading) setOpen(groupHeading, false);
                        });

                        setOpen(heading, willOpen);
                    };

                    heading.addEventListener('click', toggle);
                    heading.addEventListener('keydown', event => {
                        if(event.key !== 'Enter' && event.key !== ' ') return;

                        event.preventDefault();
                        toggle();
                    });
                });
            });
        },

        initLegalDocs(scope = document){
            const root = scope instanceof Element || scope instanceof Document
                ? scope
                : document;

            root.querySelectorAll('[data-legal-page]').forEach(page => {
                if(page.dataset.legalBound === '1') return;
                page.dataset.legalBound = '1';

                const activate = key => {
                    if(!key) return;

                    page.dataset.activeLegal = key;

                    page.querySelectorAll('[data-legal-doc]').forEach(doc => {
                        doc.classList.toggle('active', doc.dataset.legalDoc === key);
                    });

                    page.querySelectorAll('[data-legal-tab]').forEach(tab => {
                        tab.classList.toggle('active', tab.dataset.legalTab === key);
                    });
                };

                page.querySelectorAll('[data-legal-tab]').forEach(tab => {
                    tab.addEventListener('click', () => {
                        activate(tab.dataset.legalTab);
                    });
                });

                activate(page.dataset.activeLegal || page.querySelector('[data-legal-tab]')?.dataset.legalTab);
            });
        }

    };

    function initScrollBehaviour(scope) {
        if(activeScrollHandler){
            window.removeEventListener('scroll', activeScrollHandler);
            window.removeEventListener('resize', activeScrollHandler);
            activeScrollHandler = null;
        }

        if(scrollFrame){
            window.cancelAnimationFrame(scrollFrame);
            scrollFrame = null;
        }

        const siteHeader = document.querySelector('.siteHeader');
        siteHeader?.classList.remove('heroOverlayHeader');
        siteHeader?.style.removeProperty('--hero-overlay-header-rgb');

        const root = scope instanceof Element ? scope : document;
        const staticHeroes = root.matches?.('section.hero')
            ? [root]
            : Array.from(root.querySelectorAll('section.hero'));

        staticHeroes.forEach(ensureHeroScrollCue);

        const cinematicPage = root.matches?.('.cinematicPage')
            ? root
            : root.querySelector('.cinematicPage');
        const scrollHeroPage = root.matches?.('.scrollHeroPage')
            ? root
            : root.querySelector('.scrollHeroPage');
        const normalScrollPage = root.matches?.('.normalScrollPage')
            ? root
            : root.querySelector('.normalScrollPage');

        let update = null;

        if(cinematicPage){
            update = initCinematicPage(cinematicPage);
        } else if(scrollHeroPage){
            update = initScrollHeroPage(scrollHeroPage);
        } else if(normalScrollPage){
            update = initNormalScrollPage(normalScrollPage);
        }

        if(!update) return;

        activeScrollHandler = () => {
            if(scrollFrame) return;

            scrollFrame = window.requestAnimationFrame(() => {
                scrollFrame = null;
                update();
            });
        };

        window.addEventListener('scroll', activeScrollHandler, {passive:true});
        window.addEventListener('resize', activeScrollHandler);
        activeScrollHandler();
    }

    function getScrollProgress(page) {
        const rect = page.getBoundingClientRect();
        const distance = Math.max(page.offsetHeight - window.innerHeight, 1);

        return Math.min(Math.max(-rect.top / distance, 0), 1);
    }

    function ensureHeroScrollCue(hero) {
        if(!(hero instanceof Element) || !hero.matches('section.hero')) return null;

        let canvas = hero.querySelector(':scope > .canvas');
        if(!canvas){
            canvas = document.createElement('div');
            canvas.className = 'canvas';
            hero.appendChild(canvas);
        }

        let cue = canvas.querySelector(':scope > .heroScrollCue');
        if(!cue){
            cue = document.createElement('button');
            cue.className = 'heroScrollCue';
            cue.type = 'button';
            cue.setAttribute('aria-label', 'Scroll down');

            const mouse = document.createElement('span');
            mouse.className = 'heroScrollCue__mouse';
            cue.appendChild(mouse);
            canvas.appendChild(cue);
        }

        if(!cue.dataset.scrollCueBound){
            cue.dataset.scrollCueBound = 'true';
            cue.addEventListener('click', () => {
                const page = hero.closest('.scrollHeroPage');
                const nextSection = page?.querySelector(':scope > section:not(.hero)')
                    || page?.nextElementSibling
                    || hero.nextElementSibling;
                const targetTop = nextSection
                    ? window.scrollY + nextSection.getBoundingClientRect().top
                    : window.scrollY + window.innerHeight;
                const snapToTarget = () => {
                    const root = document.documentElement;
                    const previousBehaviour = root.style.scrollBehavior;

                    root.style.scrollBehavior = 'auto';
                    window.scrollTo(0, targetTop);
                    window.requestAnimationFrame(() => {
                        root.style.scrollBehavior = previousBehaviour;
                    });
                };

                window.scrollTo({
                    top: targetTop,
                    behavior: 'smooth'
                });

                const snapTimer = window.setTimeout(snapToTarget, 700);

                if('onscrollend' in window){
                    window.addEventListener('scrollend', () => {
                        window.clearTimeout(snapTimer);
                        snapToTarget();
                    }, {once:true});
                }
            });
        }

        return cue;
    }

    function ensureScrollHeroMarkup(page) {
        const source = page.dataset.src || page.dataset.videoSrc || '';
        let hero = page.querySelector(':scope > .hero');
        let welcome = page.querySelector(':scope > .welcomeBox');
        let messages = Array.from(page.querySelectorAll(':scope > .messageBox'));
        const isVideo = /\.(mp4|webm|ogg)(?:[?#].*)?$/i.test(source);

        if(!hero){
            hero = document.createElement('section');
            hero.className = 'hero dotted darken';

            if(source && isVideo){
                const video = document.createElement('video');
                const videoSource = document.createElement('source');

                video.className = 'bg-video';
                video.autoplay = true;
                video.muted = true;
                video.loop = true;
                video.playsInline = true;
                video.preload = 'auto';
                video.setAttribute('aria-hidden', 'true');
                videoSource.src = source;
                videoSource.type = source.endsWith('.webm') ? 'video/webm' : 'video/mp4';
                video.appendChild(videoSource);
                hero.appendChild(video);
            } else if(source){
                const image = document.createElement('img');

                image.className = 'bg-image';
                image.src = source;
                image.alt = page.dataset.alt || '';
                image.decoding = 'async';
                image.setAttribute('aria-hidden', image.alt ? 'false' : 'true');
                hero.appendChild(image);
            }

            const canvas = document.createElement('div');
            canvas.className = 'canvas';

            if(welcome){
                canvas.appendChild(welcome);
            }

            messages.forEach(message => canvas.appendChild(message));

            hero.appendChild(canvas);
            page.insertBefore(hero, page.firstChild);
        } else if(source){
            const videoSource = hero.querySelector('.bg-video source');
            const image = hero.querySelector('.bg-image');

            if(videoSource && videoSource.getAttribute('src') !== source){
                videoSource.src = source;
                videoSource.closest('video')?.load();
            } else if(image && image.getAttribute('src') !== source){
                image.src = source;
            }
        }

        if(!welcome){
            welcome = hero.querySelector('.welcomeBox');
        }

        messages = Array.from(new Set([
            ...messages,
            ...hero.querySelectorAll('.messageBox')
        ]));

        let canvas = hero.querySelector(':scope > .canvas');
        if(!canvas){
            canvas = document.createElement('div');
            canvas.className = 'canvas';
            hero.appendChild(canvas);
        }

        if(canvas && welcome && welcome.parentElement !== canvas){
            canvas.appendChild(welcome);
        }

        messages.forEach(message => {
            if(message.parentElement !== canvas) canvas.appendChild(message);
        });

        ensureHeroScrollCue(hero);

        return hero;
    }

    function initScrollHeroPage(page) {
        const hero = ensureScrollHeroMarkup(page);
        const header = document.querySelector('.siteHeader');
        const welcomeBox = hero.querySelector('.welcomeBox');
        const messageBoxes = Array.from(hero.querySelectorAll('.messageBox'));
        const hasMessages = messageBoxes.length > 0;
        const hasOverlay = Boolean(welcomeBox) || hasMessages;
        const returnButton = page.querySelector(':scope > .messageReturnButton') || hero.querySelector('.messageReturnButton');
        const video = hero.querySelector('.bg-video');
        let mediaReady = !video || video.readyState >= 2;

        if(video && !mediaReady){
            video.addEventListener('loadeddata', () => {
                mediaReady = true;
                activeScrollHandler?.();
            }, {once:true});
        }

        return function updateScrollHeroPage() {
            const rect = page.getBoundingClientRect();
            const rawProgress = Math.max(-rect.top / window.innerHeight, 0);
            const progress = Math.min(rawProgress, 1);

            page.style.setProperty('--hero-scale', String(1 - progress * .22));
            page.style.setProperty('--hero-opacity', String(1 - progress * .55));
            page.style.setProperty('--hero-brightness', String(1 + progress * .45));
            page.style.setProperty('--hero-gray', String(progress * .65));
            page.style.setProperty('--hero-blur', `${progress * 4}px`);
            page.style.setProperty('--hero-scroll-cue-opacity', String(Math.max(1 - rawProgress * 4, 0)));

            if(welcomeBox){
                page.style.setProperty('--welcome-y', `${progress * -86}vh`);
                page.style.setProperty('--welcome-opacity', String(Math.max(1 - progress / .72, 0)));
                page.style.setProperty('--welcome-scale', String(1 - progress * .08));
            }

            if(hasMessages){
                const segmentSize = 1 / messageBoxes.length;

                messageBoxes.forEach((messageBox, index) => {
                    const localProgress = Math.min(Math.max(
                        (progress - index * segmentSize) / segmentSize,
                        0
                    ), 1);
                    const enterProgress = Math.min(localProgress / .25, 1);
                    const exitProgress = Math.min(Math.max((localProgress - .75) / .25, 0), 1);
                    const direction = messageBox.classList.contains('left') ? -1 : 1;

                    messageBox.style.setProperty('--message-x', `${direction * (1 - enterProgress) * 105}%`);
                    messageBox.style.setProperty('--message-opacity', String(enterProgress * (1 - exitProgress)));
                });
            }

            if(hasOverlay){
                const buttonProgress = Math.min(Math.max((rawProgress - 1) / .12, 0), 1);
                const rootStyle = getComputedStyle(document.documentElement);
                const headerStyle = header ? getComputedStyle(header) : null;
                const mainRgb = (rootStyle.getPropertyValue('--m') || '184, 175, 108')
                    .split(',').map(value => Number.parseFloat(value.trim()));
                const baseRgb = (headerStyle?.getPropertyValue('--head-back-rgb') || '255, 255, 255')
                    .split(',').map(value => Number.parseFloat(value.trim()));
                const mixedRgb = mainRgb.map((value, index) =>
                    Math.round(value + ((baseRgb[index] ?? 255) - value) * progress)
                );

                page.style.setProperty('--message-button-opacity', String(buttonProgress * .85));
                page.style.setProperty('--message-button-y', `${-8 + buttonProgress * 8}px`);
                page.style.setProperty('--message-button-events', buttonProgress > .95 ? 'auto' : 'none');
                returnButton?.setAttribute('aria-hidden', buttonProgress > .5 ? 'false' : 'true');

                if(header && mediaReady && progress < 1){
                    header.classList.add('heroOverlayHeader');
                    header.style.setProperty('--hero-overlay-header-rgb', mixedRgb.join(', '));
                } else if(header){
                    header.classList.remove('heroOverlayHeader');
                    header.style.removeProperty('--hero-overlay-header-rgb');
                }
            }
        };
    }

    function initNormalScrollPage(page) {
        return function updateNormalScrollPage() {
            page.style.setProperty('--scroll-progress', String(getScrollProgress(page)));
        };
    }

    function initCinematicPage(page) {
        return function updateCinematicPage() {
            const progress = getScrollProgress(page);

            page.style.setProperty('--hero-scale', String(1 + progress * .08));
            page.style.setProperty('--hero-opacity', String(1 - progress * .35));
            page.style.setProperty('--hero-gray', String(progress * .7));
            page.style.setProperty('--hero-bright', String(1 + progress * .55));
            page.style.setProperty('--hero-blur', `${progress * 5}px`);
            page.style.setProperty('--hero-fade', String(progress * .9));
            page.style.setProperty('--title-opacity', String(Math.max(1 - progress * 1.6, 0)));
            page.style.setProperty('--title-y', `${progress * -100}px`);
        };
    }

    window.Site = Site;
    window.APP = Site;
    window.Preloader = Preloader;
    window.CookieConsent = CookieConsent;
    window.initScrollBehaviour = initScrollBehaviour;
    window.CarlvonLanguage = (language, direction = 'ltr') => {
        Site.changeLanguage(language, direction);
    };

    document.addEventListener('DOMContentLoaded', () => Site.init());

})();
