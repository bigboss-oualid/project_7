/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {makeStyles} from "@material-ui/core/styles";
import React from "react";
import { FieldGuesser, ListGuesser} from "@api-platform/admin";
import { useMediaQuery } from '@material-ui/core';
import {ResourceTitle} from '../helpers/resourceTitle';
import {validateName} from '../helpers/validates';
import { ImageField, ReferenceField, TextField, Edit, SimpleForm, TextInput, useNotify, useRefresh, useRedirect, Show, SimpleShowLayout, Create, ReferenceInput, SelectInput } from "react-admin";


const useStyles = makeStyles({
    price: { color: 'gold', fontWeight: 'bold', fontSize: '20px' },
    widthHeight: {transform: 'scale(0.5,0.5)'}
});
const PictureField = props => {
    const classes = useStyles();
    return <ImageField className={classes.widthHeight} {...props} />;
};


const ImageShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
            <TextField label="URI" source="id"/>
            <TextField source="name" />
            <TextField source="url" />
            <ImageField label="view" source="url" />
        </SimpleShowLayout>
    </Show>
);
const ImageEdit = props => {
    const notify = useNotify();
    const refresh = useRefresh();
    const redirect = useRedirect();

    const onSuccess = ({ data }) => {
        notify(`Changes to image "${data.name}" saved`);
        redirect('/images');
        refresh();
    };
    const onFailure = (error) => {
        notify(`Could not edit image: ${error.message}`);
        redirect('/images');
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
const ImagesList = (props) => {
    const isSmall = useMediaQuery(theme => theme.breakpoints.down('sm'));
    if (isSmall){
        return (
            <ListGuesser {...props}>
                <PictureField label="view" source="url" />
            </ListGuesser>
        );
    } else {
        return (
            <ListGuesser {...props}>
                <FieldGuesser source="name" />
                <PictureField label="view" source="url" />
                <ReferenceField label="Product name" source="product" reference="products">
                    <TextField source="name" />
                </ReferenceField>
            </ListGuesser>
        );
    }
};

const ImageCreate = props => (
    <Create undoable={false} {...props}>
        <SimpleForm>
            <TextInput  source="name" />
            <TextInput  label="path" source="url" />
            <ReferenceInput label="Product" source="product" reference="products">
                <SelectInput  optionText="name" optionValue="id"/>
            </ReferenceInput>
        </SimpleForm>
    </Create>
);

export {
    ImagesList,
    ImageShow,
    ImageEdit,
    ImageCreate
};