# pf-authn-agentless-php-sample

## build the image from source and run as a container locally

``docker build --no-cache -t pf-authn-agentless-php-sample .``

``docker run -p 80:80 pf-authn-agentless-php-sample``

## deploy from docker hub locally

``docker run -p 80:80 michaeldeller/pf-authn-agentless-php-sample``

## deploy from docker hub and expose with load balancer on kubernetes

``kubectl create deployment --image=michaeldeller/pf-authn-agentless-php-sample friendlyname``

``kubectl expose deployment friendlyname --port=80 --target-port=80 --type=LoadBalancer --name=friendlyname-lb``

# Overview of the Flow
:::mermaid
sequenceDiagram
    participant A as "Login Adapter"
    participant Z as "Agentless"
    participant B as "Policy Contract"
    participant otc as "PF API"
    A -> Z: /POST RefID = 123 ResumePath=/ext/ABC/
    note left of Z: RefID is a one-time token
    Z ->> otc: GET /ext/ref/pickup?REF={RefID}
    otc -->> Z: Return JSON payload of current state of user
    Z ->> Z: Do something with this information
    Z ->> Z: Perhaps show the user some UI
    Z ->> otc: Send information about the identity back to Ping Identity /POST /ext/ref/dropoff
    otc -->> Z: Return a dropoff refID {DropoffRefID}
    Z ->> B: 302 /POST {resumePath}?REF={DropoffRefID} 
    note left of B: Resume path came from the initial POST to the agentless. The dropoff RefID came from the drop off RefID.
B ->> B: User continues on their flow
:::
