/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from "react";

export const ResourceTitle = ({ record }) => {
    let title = ": ";
    if (record.name){
        title += record.name;
    } else if(record.username){
        title += record.username;

    }else if (record.firstName) {
        title += record.firstName + " " + record.lastName;

    }
    return <span>{record['@type']} {title.toUpperCase()}</span>;
};