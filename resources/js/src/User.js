export class User {
    constructor() {
        this._name = '';
        this._email = '';
        this._username = '';
        this._id_number = '';
        this._position = '';
        this._factory = '';

        this._contact = '';
        this._role = '';
        this._active = false;
        this._password = '';
        this._password_confirmation = '';
    }


    get password_confirmation() {
        return this._password_confirmation;
    }

    set password_confirmation(password_confirmation) {
        this._password_confirmation = password_confirmation;
    }



    get password() {
        return this._password;
    }

    set password(password) {
        this._password = password;
    }

    get active() {
        return this._active;
    }

    set active(active) {
        this._active = active;
    }

    get role() {
        return this._role;
    }

    set role(role) {
        this._role = role;
    }

    get contact() {
        return this._contact;
    }

    set contact(contact) {
        this._contact = contact;
    }

    get id_number() {
        return this._id_number;
    }

    set id_number(id_number) {
        this._id_number = id_number;
    }

    get username() {
        return this._username;
    }

    set username(username) {
        this._username = username;
    }

    get position() {
        return this._position;
    }

    set position(position) {
        this._position = position;
    }


    get name() {
        return this._name;
    }

    set name(name) {
        this._name = name;
    }

    get factory() {
        return this._factory;
    }

    set factory(factory) {
        this._factory = factory;
    }
    get email() {

        return this._email;
    }

    set email(email) {
        this._email = email;
    }


}
