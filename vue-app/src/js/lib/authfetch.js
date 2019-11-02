class AuthError extends Error {

    constructor(message = 'authorization required') {

        super(message);
        this.name = "AuthError";
    }
}


export default async function(url, params = {}, key = 'token') {

    let token = sessionStorage.getItem(key);

    if (token === null) throw new AuthError();

    try {

        url = new URL(url);

    } catch(e) {

        url = new URL(url, location.origin);

    } finally {

        if (!params.headers)
            params.headers = new Headers();

        params.headers.set('Authorization', 'Basic ' + token);

        let response = await fetch(url, params);

        if (response.status == 401) throw new AuthError();

        return response;
    }
};
