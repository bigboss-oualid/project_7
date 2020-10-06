/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


import jwtDecode from "jwt-decode";

const authProvider = {
    login: ({ username, password }) =>  {
        const request = new Request('http://localhost:8000/api/login_check', {
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
                localStorage.setItem('informations', decodedToken);
            });
    },
    getPermissions: () => {
        const role = localStorage.getItem('roles');
        return role ? Promise.resolve(role) : Promise.reject();
    },
    getIdentity: () => {
        const { userName, firstName, lastName} = JSON.parse(localStorage.getItem('informations'));
        return { userName, firstName, lastName };
    },
    logout: () => {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        localStorage.removeItem('informations');
        return Promise.resolve();
    },
    checkError: (error) => {
        const status = error.status;
        if (status === 401 || status === 403) {
            localStorage.removeItem('token');
            return Promise.reject();
        }
        return Promise.resolve();
    },
    checkAuth: () => {
        const token = localStorage.getItem('token');
        const role = localStorage.getItem('role');
        if (token) {
            if (role === 'ROLE_SUPERADMIN') {
                return Promise.resolve(role)
            } else {

                localStorage.removeItem('token');
                return Promise.reject(new Error('your are unauthorized'))
            }
        }
        localStorage.removeItem('token');
        return Promise.reject()
    }
};

export default authProvider;

