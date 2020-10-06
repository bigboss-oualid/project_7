/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import React from "react";
import {ListGuesser} from "@api-platform/admin";
import { useMediaQuery } from '@material-ui/core';
import {ResourceTitle} from '../helpers/resourceTitle';
import {validateName} from '../helpers/validates';
import { ChipField, ArrayField, SingleFieldList, TextField, Edit, Create, SimpleForm, SimpleList, TextInput, List, useNotify, useRefresh, useRedirect, Show, SimpleShowLayout } from "react-admin";



const CategoryEdit = props => {
    const notify = useNotify();
    const refresh = useRefresh();
    const redirect = useRedirect();

    const onSuccess = ({ data }) => {
        notify(`Changes to category "${data.name}" saved`);
        redirect('/categories');
        refresh();
    };
    const onFailure = (error) => {
        notify(`Could not edit category: ${error.message}`);
        redirect('/categories');
        refresh();
    };
    return (
        <Edit undoable={false} title={<ResourceTitle />}  onSuccess={onSuccess}  onFailure={onFailure} {...props}>
            <SimpleForm warnWhenUnsavedChanges >
                <TextInput disabled label="URI" source="id" />
                <TextInput  source="name" validate={validateName} />
            </SimpleForm>
        </Edit>
    );
};
const CategoryShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
            <TextField label="URI" source="id" />
            <TextField  source="name" />
            <ArrayField source="products">
                <SingleFieldList>
                    <ChipField source="name"/>
                </SingleFieldList>
            </ArrayField>

            {/*<ReferenceManyField label="products" reference="products" target="category_id">
                <SingleFieldList>
                    <ChipField source="name" />
                </SingleFieldList>
            </ReferenceManyField>*/}
        </SimpleShowLayout>
    </Show>
);
const CategoriesList = (props) => {
    const isSmall = useMediaQuery(theme => theme.breakpoints.down('sm'));
    if (isSmall){
        return (
            <List {...props}><SimpleList primaryText={record => record.name}/></List>
        );
    } else {
        return (
            <ListGuesser {...props}><ChipField source="name" /></ListGuesser>
        );
    }
};

const CategoryCreate = props => (
    <Create undoable={false} {...props}>
        <SimpleForm>
            <TextInput  source="name" />
        </SimpleForm>
    </Create>
);

export {
    CategoriesList,
    CategoryShow,
    CategoryEdit,
    CategoryCreate
};