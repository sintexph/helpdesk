import {
    Default
}
from "./Default";

export class LongText extends Default {

    constructor(paste_image) {
        super();

        if (paste_image !== null && paste_image !== undefined)
            this._paste_image = paste_image;
        else
            this._paste_image = false;

        this._advance = false;
    }

    get advance() {
        return this._advance;
    }
    set advance(advance) {
        this._advance = advance;
    }


    get paste_image() {
        return this._paste_image;
    }
    set paste_image(paste_image) {
        this._paste_image = paste_image;
    }
}
