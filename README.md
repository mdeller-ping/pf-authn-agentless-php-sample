# pf-authn-agentless-php-sample

## build the image from source and run as a container locally

``docker build --no-cache -t pf-authn-agentless-php-sample .``

``docker run -p 80:80 pf-authn-agentless-php-sample``

## deploy from docker hub locally

``docker run -p 80:80 michaeldeller/pf-authn-agentless-php-sample``

## deploy from docker hub and expose with load balancer on kubernetes

``kubectl create deployment --image=michaeldeller/pf-authn-agentless-php-sample friendlyname``

``kubectl expose deployment friendlyname --port=80 --target-port=80 --type=LoadBalancer --name=friendlyname-lb``
