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
import RichTextInput from 'ra-input-rich-text';
import { makeStyles } from '@material-ui/core/styles';
import { ReferenceField, SingleFieldList, ChipField, ImageField, TextField, Edit, Create, SimpleForm, SimpleList, ReferenceInput, SelectInput, TextInput, List, NumberInput, useNotify, useRefresh, useRedirect, required, minLength, maxLength,number, regex, email, ArrayField, Show, SimpleShowLayout, DateField, RichTextField, NumberField } from "react-admin";


const useStyles = makeStyles({
    price: { color: 'gold', fontWeight: 'bold', fontSize: '20px' },
    widthHeight: {transform: 'scale(0.5,0.5)'}
});
const PriceField = props => {
    const classes = useStyles();
    return <TextField className={classes.price} {...props} />;
};
const PictureField = props => {
    const classes = useStyles();
    return <ImageField className={classes.widthHeight} {...props} />;
};




const validateName = [required(),minLength(2), maxLength(25)];
const validateDescription = [required(), minLength(15)];
const validateDetails = [required(), minLength(10), maxLength(150)];
const validateBarcode =  regex(/^\d{8,20}$/, 'Must be between 9 & 20 numbers');
const validatePass =  regex(/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}$/, 'Password must be at least 7 characters long and contain at least one digit, one specific character one upper & lower case letter');
const validatePrice = [number(), required()];
const validateEmail = email('email is required');


const ResourceTitle = ({ record }) => {
    return <span>Product {record ? `"${record.name}"` : ''}</span>;
};



export const ProductsList = (props) => {
    const isSmall = useMediaQuery(theme => theme.breakpoints.down('sm'));
    if (isSmall){
        return (
            <List {...props}>
                <SimpleList
                    primaryText={record => record.name}
                    secondaryText={record =>  `${record.price} €`}
                    tertiaryText={record => record.details}
                />
            </List>
        );
    } else {
        return (
            <ListGuesser {...props}>
                <FieldGuesser source="name" />
                <PriceField source="price" />
                <FieldGuesser source="quantity" />
                <FieldGuesser source="details" />
                <FieldGuesser source="barcode" />
                <ArrayField source="images">
                    <SingleFieldList>
                        <PictureField source="url" />
                    </SingleFieldList>
                </ArrayField>
                <ChipField label="category" source="category.name" />
            </ListGuesser>
        );
    }
};
export const ProductShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
            <TextField source="name" />
            <NumberField source="price" />
            <NumberField source="quantity" />
            <NumberField source="barcode" />
            <ReferenceField label="Category" source="category" reference="categories">
                <ChipField source="name" />
            </ReferenceField>
            <RichTextField source="details" />
            <RichTextField source="description" />
            <DateField label="Publication date" source="createdAt" />
        </SimpleShowLayout>
    </Show>
);
export const ProductEdit = props => {
    const notify = useNotify();
    const refresh = useRefresh();
    const redirect = useRedirect();

    const onSuccess = ({ data }) => {
        notify(`Changes to product "${data.name}" saved`);
        redirect('/products');
        refresh();
    };
    const onFailure = (error) => {
        notify(`Could not edit product: ${error.message}`);
        redirect('/products');
        refresh();
    };
    return (
        <Edit undoable={false} title={<ResourceTitle />} onSuccess={onSuccess}  onFailure={onFailure} {...props}>
            <SimpleForm warnWhenUnsavedChanges>
                <TextInput disabled label="uri" source="id" />
                <TextInput  source="name" validate={validateName}  />
                <RichTextInput  source="description" validate={validateDescription} />
                <RichTextInput  source="details" validate={validateDetails} />
                <TextInput  source="barcode" validate={validateBarcode} />
                <NumberInput  source="price" validate={validatePrice} />
                <ReferenceInput  label="Category" source="category" reference="categories" >
                    <SelectInput optionText="name" optionValue="id" />
                </ReferenceInput>
            </SimpleForm>
        </Edit>
    );
};

export const ProductCreate = props => (
    <Create undoable={false} {...props}>
        <SimpleForm>
            <TextInput  source="name" />
            <RichTextInput  source="description" />
            <RichTextInput  source="details" />
            <TextInput  source="barcode" />
            <NumberInput  source="price" />
            <ReferenceInput label="Category" source="productId" reference="categories">
                <SelectInput  optionText="name" optionValue="category_id"/>
            </ReferenceInput>
        </SimpleForm>
    </Create>
);