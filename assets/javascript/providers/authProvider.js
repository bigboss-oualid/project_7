/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


import jwtDecode from "jwt-decode";
import { LOGIN_API } from "../config";

const authProvider = {
    login: ({ username, password }) =>  {
        const request = new Request(LOGIN_API, {
            method: 'POST',
            body: JSON.stringify({ username, password }),
            headers: new Headers({ 'Content-Type': 'application/json' }),
        });
        return fetch(request)
            .then(response => {
                if (response.status < 200 || response.status >= 300) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then(({ token }) => {
                const decodedToken = jwtDecode(token);
                localStorage.setItem('token', token);
                localStorage.setItem('role', decodedToken.roles);
                if (localStorage.getItem('role') !== 'ROLE_SUPERADMIN') {
                    return Promise.reject(new Error('Unauthorized'));
                }
            });
    },
    getPermissions: () => {
        const role = localStorage.getItem('roles');
        return role ? Promise.resolve(role) : Promise.reject();
    },
    getIdentity: () => {
        const token = localStorage.getItem('token');
        const {username, lastName, firstName} = jwtDecode(token);
        return {username, lastName, firstName};
    },
    logout: () => {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        return Promise.resolve();
    },
    checkError: (error) => {
        const status = error.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('token');
            localStorage.removeItem('role');
            return Promise.reject();
        }
        return Promise.resolve();
    },
    checkAuth: () => {
        const token = localStorage.getItem('token');

        if (token) {
            const {exp: expiration} = jwtDecode(token);
            if (expiration * 1000 > new Date().getTime()) {
                return Promise.resolve();
            }
            return Promise.reject(new Error('Your token is expired'));
        }
        return Promise.reject();
    }
};

export default authProvider;

