export class Ticket {
    constructor() {
        this._sender_name = '';
        this._sender_email = '';
        this._sender_carbon_copies = '';
        this._sender_internet_protocol_address = '';
        this._sender_phone = '';
        this._sender_factory = '';

        this._title = '';
        this._content = '';
        this._urgency ='1';
        this._attachments = [];
    }


    get attachments() {
        return this._attachments;
    }

    set attachments(attachments) {
        this._attachments = attachments;
    }

    get urgency() {
        return this._urgency;
    }

    set urgency(urgency) {
        this._urgency = urgency;
    }

    get content() {
        return this._content;
    }

    set content(content) {
        this._content = content;
    }

    get title() {
        return this._title;
    }

    set title(title) {
        this._title = title;
    }

    get sender_internet_protocol_address() {
        return this._sender_internet_protocol_address;
    }

    set sender_internet_protocol_address(sender_internet_protocol_address) {
        this._sender_internet_protocol_address = sender_internet_protocol_address;
    }

    get carbon_copies() {
        return this._carbon_copies;
    }

    set carbon_copies(carbon_copies) {
        this._carbon_copies = carbon_copies;
    }

    get sender_phone() {
        return this._sender_phone;
    }

    set sender_phone(sender_phone) {
        this._sender_phone = sender_phone;
    }


    get sender_name() {
        return this._sender_name;
    }

    set sender_name(sender_name) {
        this._sender_name = sender_name;
    }

    get sender_factory() {
        return this._sender_factory;
    }

    set sender_factory(sender_factory) {
        this._sender_factory = sender_factory;
    }
    get sender_email() {

        return this._sender_email;
    }

    set sender_email(sender_email) {
        this._sender_email = sender_email;
    }


}
