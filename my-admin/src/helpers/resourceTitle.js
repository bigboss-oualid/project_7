/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from "react";

export const ResourceTitle = ({ record }) => {
    return <span>{record['@type']} {record.name ? `: ${record.name}` : `: ${record.firstName} ${record.lastName}`}</span>;
};