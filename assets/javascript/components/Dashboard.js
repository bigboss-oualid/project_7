/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import * as React from "react";
import { Card, CardContent, CardHeader } from '@material-ui/core';
import {makeStyles} from "@material-ui/core/styles";

export default () => {
    const useStyles = makeStyles(() => ({
        title: {
            marginTop: '15px',
            fontWeight: 'bold',
            color: 'green',
            textAlign: 'center',
            fontSize: '30px'
        },
        parag: {
            textAlign: 'center',
            fontSize: '24px',
            marginBottom: '150px',
        },
        center: {
            marginTop: '15px',
            display: 'block',
            marginLeft: 'auto',
            marginRight: 'auto',
            width: '50%',
        }

    }));
    const classes = useStyles();
    return (
        <Card>
            <img src="../images/logo.png" alt="BileMo logo" title="Logo de BileMo" className={classes.center} />
            <CardHeader className={classes.title} title="Welcome to the Dashboard of Bilemo API" />
            <CardContent className={classes.parag}>Here your can easily manage the content of your API-BileMo</CardContent>
        </Card>
    );
};
