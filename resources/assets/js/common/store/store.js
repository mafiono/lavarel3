import app from './app';
import user from './user';
import promotions from './promotions';
import mobile from './mobile';

export default {
    app,
    user,
    promotions,
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