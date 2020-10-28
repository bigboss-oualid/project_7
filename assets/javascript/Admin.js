import React from "react";
import { Redirect, Route } from "react-router-dom";
import { HydraAdmin, ResourceGuesser, hydraDataProvider as baseHydraDataProvider, fetchHydra as baseFetchHydra } from "@api-platform/admin";
import parseHydraDocumentation from "@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation";
import ProductIcon from '@material-ui/icons/PhoneAndroid';
import CategoryIcon from '@material-ui/icons/Category';
import ImageIcon from '@material-ui/icons/Image';
import UserIcon from '@material-ui/icons/Group';
import CustomerIcon from '@material-ui/icons/Contacts';
import authProvider from "./providers/authProvider";
import Dashboard from "./components/Dashboard";
import {ProductCreate, ProductEdit, ProductShow, ProductsList} from "./components/products";
import {UserCreate, UserEdit, UserShow, UsersList} from "./components/users";
import {CustomerCreate, CustomerEdit, CustomerShow, CustomersList} from "./components/customers";
import {CategoryCreate, CategoryEdit,CategoryShow, CategoriesList} from "./components/categories";
import {ImageEdit, ImageShow, ImagesList, ImageCreate} from "./components/images";
import { API_URL } from "./config";

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


const dataProvider = baseHydraDataProvider(API_URL, fetchHydra, apiDocumentationParser,true);

export default () => (
    <HydraAdmin
        dashboard={ Dashboard }
        dataProvider={ dataProvider }
        authProvider={ authProvider }
        entrypoint={ API_URL }>
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
            create={ImageCreate}
            icon={ImageIcon}
        />
        <ResourceGuesser
            name="categories"
            list={CategoriesList}
            show={CategoryShow}
            edit={CategoryEdit}
            create={CategoryCreate}
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