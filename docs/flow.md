```mermaidjs
sequenceDiagram
    participant Z as Agentless Adapter
    participant B as Policy Contract
    participant otc as Ping Federate API
    B ->> Z: /POST RefID = {PickupRefID} ResumePath={resumePath}
    note left of Z: RefID is a one-time token
    Z ->> otc: GET /ext/ref/pickup?REF={PickupRefID}
    otc -->> Z: Return JSON payload of current state of user
    Z ->> Z: Do something with this information
    Z ->> Z: Perhaps show the user some UI
    Z ->> otc: Send information about the identity back to Ping Identity /POST /ext/ref/dropoff
    otc -->> Z: Return a dropoff refID {DropoffRefID}
    Z ->> B: 302 /POST {resumePath}?REF={DropoffRefID} 
    note left of B: Resume path came from the initial POST to the agentless. The dropoff RefID came from the drop off RefID.
    B ->> B: User continues on their flow
```