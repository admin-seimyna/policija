import { get, set} from './_helpers';

export default class Permission {
    store;
    props;

    /**
     *
     * @param store
     */
    constructor(props, store) {
        this.store = store;
        this.props = Object.assign({
            adminRoleName: 'admin',
            userRoleName: 'user',
        }, props);
    }


    /**
     * Has permission
     * @param key
     * @return {*}
     */
    has(key) {
        const user = this.store.getters['auth/user'];
        if (!user) return false;
        if (user.role === this.props.adminRoleName) return true;
        if(!user.user_group || !user.user_group.mapped_permissions) return false;
        return !!get(key, user.user_group.mapped_permissions);
    }

    /**
     * Has one or more permissions
     * @param permissions
     * @returns {boolean}
     */
    hasAny(permissions) {
        for(const permission of permissions) {
            if (this.has(permission)) {
                return true;
            }
        }
        return false;
    }
}
