export class Task {
    constructor() {
        this._name = '';
        this._description = '';
        this._start_date = '';
        this._due_date = '';
        this._assigned_to = '';
        this._state = '';
        this._label = '';
        this._priority = '';
        this._remarks = '';
        this._project = new Project;
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

    get remarks() {
        return this._remarks;
    }

    set remarks(remarks) {
        this._remarks = remarks;
    }


    get project() {
        return this._project;
    }

    set project(project) {
        this._project = project;
    }


    get priority() {
        return this._priority;
    }

    set priority(priority) {
        this._priority = priority;
    }


    get label() {
        return this._label;
    }

    set label(label) {
        this._label = label;
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



    get assigned_to() {
        return this._assigned_to;
    }

    set assigned_to(assigned_to) {
        this._assigned_to = assigned_to;
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
