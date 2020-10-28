/*
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


import { required, minLength, maxLength,regex, number, email } from "react-admin";

const validateName = [required(),minLength(2), maxLength(25)];
const validateDescription = [required(), minLength(15)];
const validateDetails = [required(), minLength(10), maxLength(150)];
const validateBarcode =  [required(), regex(/^\d{8,20}$/, 'Must be between 9 & 20 numbers')];
const validateUrl =  [required(), regex(/^[^\/]([A-z0-9-_+]+\/)*([A-z0-9]+\..*)$/, 'The path is not correct'), regex(/.*(png|jpeg|jpg)$/, 'Only "png", "jpeg" & "jpg" formats are allowed')];
const validatePass = [required(), regex(/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}$/, 'Password must be at least 7 characters long and contain at least one digit, one specific character one upper & lower case letter')];
const validatePrice = [number(), required()];
const validateEmail = [required(), email('email format is required')];

export {
    validateName,
    validateDescription,
    validateDetails,
    validateBarcode,
    validatePass,
    validatePrice,
    validateEmail,
    validateUrl
};