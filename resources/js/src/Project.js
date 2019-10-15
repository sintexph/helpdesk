export class Project {
    constructor() {
        this._name = '';
        this._description = '';
        this._start_date = '';
        this._due_date = '';
        this._requested_by = '';
        this._state = '';
        this._tags = [];
        this._followers = [];
        this._is_public = false;



        this._attachments = [];
        this._attachments_input = null;
        this._to_be_deleted_attachments = [];

    }



    get to_be_deleted_attachments() {
        return this._to_be_deleted_attachments;
    }

    set to_be_deleted_attachments(to_be_deleted_attachments) {
        this._to_be_deleted_attachments = to_be_deleted_attachments;
    }


    get attachments_input() {
        return this._attachments_input;
    }

    set attachments_input(attachments_input) {
        this._attachments_input = attachments_input;
    }



    get attachments() {
        return this._attachments;
    }

    set attachments(attachments) {
        this._attachments = attachments;
    }





    get is_public() {
        return this._is_public;
    }

    set is_public(is_public) {
        this._is_public = is_public;
    }

    get followers() {
        return this._followers;
    }

    set followers(followers) {
        this._followers = followers;
    }


    get tags() {
        return this._tags;
    }

    set tags(tags) {
        this._tags = tags;
    }


    get state() {
        return this._state;
    }

    set state(state) {
        this._state = state;
    }


    get start_date() {
        return this._start_date;
    }

    set start_date(start_date) {
        this._start_date = start_date;
    }



    get due_date() {
        return this._due_date;
    }

    set due_date(due_date) {
        this._due_date = due_date;
    }



    get requested_by() {
        return this._requested_by;
    }

    set requested_by(requested_by) {
        this._requested_by = requested_by;
    }


    get name() {
        return this._name;
    }

    set name(name) {
        this._name = name;
    }


    get description() {

        return this._description;
    }

    set description(description) {
        this._description = description;
    }


}
