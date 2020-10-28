/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from 'react';
import ReactDOM from 'react-dom';
import '../styles/index.css';
import Admin from './Admin';

import * as serviceWorker from './serviceWorker';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

//console.log('Hello Webpack Encore! Edit me in assets/javascript/app.js');

ReactDOM.render(
    <React.StrictMode>
        <Admin />
    </React.StrictMode>,
    document.getElementById('root')
);
// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();