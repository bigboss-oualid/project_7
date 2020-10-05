import React from "react";
import { Redirect, Route } from "react-router-dom";
import { HydraAdmin, FieldGuesser, ListGuesser, ResourceGuesser, hydraDataProvider as baseHydraDataProvider, fetchHydra as baseFetchHydra } from "@api-platform/admin";
import parseHydraDocumentation from "@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation";
import { useMediaQuery } from '@material-ui/core';
import ProductIcon from '@material-ui/icons/PhoneAndroid';
import CategoryIcon from '@material-ui/icons/Category';
import ImageIcon from '@material-ui/icons/Image';
import UserIcon from '@material-ui/icons/Group';
import CustomerIcon from '@material-ui/icons/Contacts';
import Dashboard from "./components/Dashboard";
import {ProductCreate, ProductEdit, ProductShow, ProductsList} from "./components/products";
import authProvider from "./providers/authProvider";
import { makeStyles } from '@material-ui/core/styles';
import { ReferenceField, SingleFieldList, ChipField, ImageField, TextField, EmailField, Edit, Create, SimpleForm, SimpleList, ReferenceInput, SelectInput, TextInput, List, useNotify, useRefresh, useRedirect, required, minLength, maxLength, number, regex, email, ArrayField, Show, SimpleShowLayout, DateField } from "react-admin";

//console.log('walid', process.env);


const useStyles = makeStyles({
    price: { color: 'gold', fontWeight: 'bold', fontSize: '20px' },
    widthHeight: {transform: 'scale(0.3,0.3)'}
});
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







const UserShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
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






const CustomerShow = (props) => (
    <Show title={<ResourceTitle />} {...props}>
        <SimpleShowLayout>
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
const CategoryList = (props) => {
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





const apiUrl = "http://localhost:8000/api";
const fetchHeaders = { Authorization: `Bearer ${window.localStorage.getItem("token")}` };
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
    ...options,
    headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
    .then(
        ({ api }) => ({ api }),
        (result) => {
            if (result.status === 401) {
                return Promise.resolve({
                    api: result.api,
                    customRoutes: [
                        <Route path="/" render={() => {
                            return window.localStorage.getItem("token") ? window.location.reload() : <Redirect to="/login" />
                        }} />
                    ],
                });
            } else {
                return Promise.reject(result);
            }
        },
    );


const dataProvider = baseHydraDataProvider(apiUrl, fetchHydra, apiDocumentationParser,true);

export default () => (
    <HydraAdmin
        dashboard={ Dashboard }
        dataProvider={ dataProvider }
        authProvider={ authProvider }
        entrypoint={ apiUrl }>
        <ResourceGuesser
            name="products"
            list={ProductsList}
            show={ProductShow}
            edit={ProductEdit}
            create={ProductCreate}
            icon={ProductIcon}
        />
        <ResourceGuesser
            name="images"
            list={ImagesList}
            show={ImageShow}
            edit={ImageEdit}
            icon={ImageIcon}
        />
        <ResourceGuesser
            name="categories"
            list={CategoryList}
            show={CategoryShow}
            edit={CategoryEdit}
            icon={CategoryIcon}
        />
        <ResourceGuesser
            name="users"
            list={UsersList}
            show={UserShow}
            edit={UserEdit}
            create={UserCreate}
            icon={UserIcon}
        />
        <ResourceGuesser
            name="customers"
            list={CustomersList}
            show={CustomerShow}
            edit={CustomerEdit}
            create={CustomerCreate}
            icon={CustomerIcon}
        />
    </HydraAdmin>
);