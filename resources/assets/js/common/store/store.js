import app from './app';
import user from './user';
import promotions from './promotions';
import golodeouro from './golodeouro';
import mobile from './mobile';

export default {
    app,
    user,
    promotions,
    golodeouro,
    mobile,
    init() {
        for (let state in this) {
            if (this.hasOwnProperty(state) && this[state].hasOwnProperty('init')) {
                this[state].init();
            }
        }

        delete this['init'];
    }
}