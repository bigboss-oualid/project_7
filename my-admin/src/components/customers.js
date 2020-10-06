/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from "react";
import { FieldGuesser, ListGuesser} from "@api-platform/admin";
import { useMediaQuery } from '@material-ui/core';
import {ResourceTitle} from '../helpers/resourceTitle';
import {validateName, validatePass, validateEmail} from '../helpers/validates';
import { ChipField, EmailField, TextField, Edit, Create, SimpleForm, SimpleList,  TextInput, List, useNotify, useRefresh, useRedirect, Show, SimpleShowLayout, DateField} from "react-admin";


const CustomerShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
            <TextField label="URI" source="id" />
            <TextField source="firstName" />
            <TextField source="lastName" />
            <TextField source="userName" />
            <ChipField source="roles" />
            <EmailField source="email" />
            <TextField source="company" />
            <DateField source="createdAt" />
        </SimpleShowLayout>
    </Show>
);
const CustomerEdit = props => {
    const notify = useNotify();
    const refresh = useRefresh();
    const redirect = useRedirect();

    const onSuccess = ({ data }) => {
        notify(`Changes to customer "${data.firstName}" saved`);
        redirect('/customers');
        refresh();
    };
    const onFailure = (error) => {
        notify(`Could not edit customer: ${error.message}`);
        redirect('/customers');
        refresh();
    };
    return (
        <Edit undoable={false} title={<ResourceTitle />} onSuccess={onSuccess}  onFailure={onFailure} {...props}>
            <SimpleForm warnWhenUnsavedChanges >
                <TextInput disabled label="URI" source="id" />
                <TextInput  source="firstName" validate={validateName}  />
                <TextInput  source="lastName" validate={validateName}  />
                <TextInput type="password" source="password" validate={validatePass}  />
                <TextInput type="email" source="email" validate={validateEmail}  />
                <TextInput  source="company" validate={validateName}  />
            </SimpleForm>
        </Edit>
    );
};
const CustomerCreate = props => (
    <Create undoable={false} {...props}>
        <SimpleForm>
            <TextInput source="firstName" validate={validateName} />
            <TextInput source="lastName" validate={validateName} />
            <TextInput source="username" validate={validateName} />
            <TextInput type="email" source="email" validate={validateEmail} />
            <TextInput source="company" validate={validateName} />
            <TextInput type="password" source="password" validate={validatePass}  />
        </SimpleForm>
    </Create>
);
const CustomersList = (props) => {
    const isSmall = useMediaQuery(theme => theme.breakpoints.down('sm'));
    if (isSmall){
        return (
            <List {...props}>
                <SimpleList
                    primaryText={record => record.email}
                    secondaryText={record =>  record.company}
                    tertiaryText={record =>  new Date(record.createdAt).toLocaleDateString()}
                />
            </List>
        );
    } else {
        return (
            <ListGuesser {...props}>
                <FieldGuesser source="username" />
                <FieldGuesser source="firstName" />
                <FieldGuesser source="lastName" />
                <EmailField source="email" />
                <ChipField source="company" />
                <FieldGuesser source="createdAt" />
                <ChipField source="roles" title="name" />
            </ListGuesser>
        );
    }
};


export {
    CustomersList,
    CustomerShow,
    CustomerEdit,
    CustomerCreate
};