
export default {

    getParam(state, getters) {
        return name => {

            return state.params[name];
        };
    },
    getUserName(state, getters) {

        return _ => {

            let token = sessionStorage.getItem('token');

            if (token) {
                let str = atob(token);

                let res = str.split(":");

                return res[0];
            }

            return undefined;
        }
    },
    getUserPassword(state, getters) {

        return _ => {

            let token = sessionStorage.getItem('token');

            if (token) {
                let str = atob(token);

                let res = str.split(":");

                return res[1];
            }

            return undefined;
        }
    }
};
