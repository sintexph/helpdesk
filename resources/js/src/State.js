export class State {
    static get PENDING(){ return 1; }
    static get CATERED(){ return 2; }
    static get PROCESSING(){ return 3; }
    static get SOLVED(){ return 4; }
    static get CLOSED(){ return 5; }
    static get HOLD(){ return 6; }
    static get CANCELLED(){ return 7; }
    static get ESCALATED(){ return 8; }
    static get APPLIED_APPROVAL(){ return 9; }
    static get APPROVAL_CANCELLED(){ return 10; }
    static get APPROVED(){ return 11; }
    static get REJECTED(){ return 12; }

    static get FOR_CANVASSING(){ return 13; }
    static get CREATED_PURCHASE_REQUISITION(){ return 14; }
    static get PROCESSING_PURCHASE_ORDER(){ return 15; }
    static get DELIVERED(){ return 16; }
    static get READY_FOR_RELEASE(){ return 17; }

    static get UN_HOLD(){ return 18; }

    static get COMPLETED(){ return 19; }
}
