/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from "react";
import { FieldGuesser, ListGuesser} from "@api-platform/admin";
import {ResourceTitle} from '../helpers/resourceTitle';
import {validateName, validateEmail} from '../helpers/validates';
import { useMediaQuery } from '@material-ui/core';
import { ChipField, EmailField, TextField, Edit, Create, SimpleForm, SimpleList, ReferenceInput, SelectInput, TextInput, List, useNotify, useRefresh, useRedirect, Show, SimpleShowLayout, DateField } from "react-admin";


const UserShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
            <TextField label="URI" source="id" />
            <TextField source="firstName" />
            <TextField source="lastName" />
            <EmailField source="email" />
            <TextField source="company" />
            <DateField source="createdAt" />
        </SimpleShowLayout>
    </Show>
);
const UserEdit = props => {
    const notify = useNotify();
    const refresh = useRefresh();
    const redirect = useRedirect();

    const onSuccess = ({ data }) => {
        notify(`Changes to user "${data.firstName}" saved`);
        redirect('/users');
        refresh();
    };
    const onFailure = (error) => {
        notify(`Could not edit user: ${error.message}`);
        redirect('/users');
        refresh();
    };
    return (
        <Edit undoable={false} title={<ResourceTitle />} onSuccess={onSuccess}  onFailure={onFailure} {...props}>
            <SimpleForm warnWhenUnsavedChanges >
                <TextInput disabled label="URI" source="id" />
                <TextInput  source="firstName" validate={validateName} />
                <TextInput  source="lastName" validate={validateName} />
                <TextInput type="email" source="email" validate={validateEmail}  />
                <TextInput  source="company" validate={validateName} />
            </SimpleForm>
        </Edit>
    );
};
const UserCreate = props => (
    <Create undoable={false} {...props}>
        <SimpleForm>
            <TextInput  source="firstName" validate={validateName} />
            <TextInput  source="lastName" validate={validateName} />
            <TextInput type="email" source="email" validate={validateEmail}  />
            <ReferenceInput  label="Customer" source="customer" reference="customers" >
                <SelectInput optionText="firstName" optionValue="id" />
            </ReferenceInput>
        </SimpleForm>
    </Create>
);

const UsersList = (props) => {
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
                <FieldGuesser source="firstName" />
                <FieldGuesser source="lastName" />
                <EmailField source="email" />
                <ChipField source="company" />
                <FieldGuesser source="createdAt" />
            </ListGuesser>
        );
    }
};

export {
    UsersList,
    UserShow,
    UserEdit,
    UserCreate
};