class Lightbox {
    private settings: LightboxSettings = {
        target: 'lightbox-container'
    };

    get containers(): NodeListOf<HTMLDivElement> {
        const targets: NodeListOf<HTMLDivElement> = document.querySelectorAll(`.${this.settings.target}`);

        return targets;
    }

    init(): void {
        this.containers.forEach((target) => this.attachEvents(target));
    }

    attachEvents(target: HTMLElement): void {
        target.addEventListener('click', (e: Event) => this.closeOnClickOut(e.target));

        const closeButton: HTMLButtonElement | null = target.querySelector('button');
        if (closeButton === null) {
            return;
        }

        closeButton.addEventListener('click', (e: Event) => {
            const element = e.currentTarget;

            if (!(element instanceof HTMLElement)) {
                return;
            }

            const parentContainer: HTMLDivElement | null = element.closest(`.${this.settings.target}`);

            if (parentContainer === null) {
                return;
            }

            this.hide(parentContainer);
        });
    }

    /**
     * Identify if event is valid for closing lightbox container
     *
     * @param {(EventTarget|null)} eventTarget
     * @returns {void}
     * @memberof Lightbox
     */
    closeOnClickOut(eventTarget: EventTarget | null): void {
        const element = eventTarget;
        if (!(element instanceof HTMLElement)) {
            return;
        }

        const isValid = element.classList.contains(this.settings.target);
        if (isValid) {
            this.hide(element);
        }
    }

    /**
     * Hide lightbox container by element
     *
     * @param {HTMLElement} target
     * @memberof Lightbox
     */
    hide(target: HTMLElement): void {
        target.classList.add('hidden');
    }

    /**
     * Show lightbox by parent element
     *
     * @param {HTMLElement} target
     * @memberof Lightbox
     */
    show(target: HTMLElement): void {
        target.classList.add('hidden');
    }
}

const lightbox = new Lightbox();
lightbox.init();

interface LightboxSettings {
    target: string;
}
