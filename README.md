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
[![](https://mermaid.ink/img/eyJjb2RlIjoic2VxdWVuY2VEaWFncmFtXG4gICAgcGFydGljaXBhbnQgWiBhcyBBZ2VudGxlc3MgQWRhcHRlclxuICAgIHBhcnRpY2lwYW50IEIgYXMgUG9saWN5IENvbnRyYWN0XG4gICAgcGFydGljaXBhbnQgb3RjIGFzIFBpbmcgRmVkZXJhdGUgQVBJXG4gICAgQiAtPj4gWjogL1BPU1QgUmVmSUQgPSB7UGlja3VwUmVmSUR9IFJlc3VtZVBhdGg9e3Jlc3VtZVBhdGh9XG4gICAgbm90ZSBsZWZ0IG9mIFo6IFJlZklEIGlzIGEgb25lLXRpbWUgdG9rZW5cbiAgICBaIC0-PiBvdGM6IEdFVCAvZXh0L3JlZi9waWNrdXA_UkVGPXtQaWNrdXBSZWZJRH1cbiAgICBvdGMgLS0-PiBaOiBSZXR1cm4gSlNPTiBwYXlsb2FkIG9mIGN1cnJlbnQgc3RhdGUgb2YgdXNlclxuICAgIFogLT4-IFo6IERvIHNvbWV0aGluZyB3aXRoIHRoaXMgaW5mb3JtYXRpb25cbiAgICBaIC0-PiBaOiBQZXJoYXBzIHNob3cgdGhlIHVzZXIgc29tZSBVSVxuICAgIFogLT4-IG90YzogU2VuZCBpbmZvcm1hdGlvbiBhYm91dCB0aGUgaWRlbnRpdHkgYmFjayB0byBQaW5nIElkZW50aXR5IC9QT1NUIC9leHQvcmVmL2Ryb3BvZmZcbiAgICBvdGMgLS0-PiBaOiBSZXR1cm4gYSBkcm9wb2ZmIHJlZklEIHtEcm9wb2ZmUmVmSUR9XG4gICAgWiAtPj4gQjogMzAyIC9QT1NUIHtyZXN1bWVQYXRofT9SRUY9e0Ryb3BvZmZSZWZJRH0gXG4gICAgbm90ZSBsZWZ0IG9mIEI6IFJlc3VtZSBwYXRoIGNhbWUgZnJvbSB0aGUgaW5pdGlhbCBQT1NUIHRvIHRoZSBhZ2VudGxlc3MuIFRoZSBkcm9wb2ZmIFJlZklEIGNhbWUgZnJvbSB0aGUgZHJvcCBvZmYgUmVmSUQuXG4gICAgQiAtPj4gQjogVXNlciBjb250aW51ZXMgb24gdGhlaXIgZmxvdyIsIm1lcm1haWQiOnsidGhlbWUiOiJkYXJrIn0sInVwZGF0ZUVkaXRvciI6dHJ1ZSwiYXV0b1N5bmMiOnRydWUsInVwZGF0ZURpYWdyYW0iOmZhbHNlfQ)](https://mermaid.live/edit#eyJjb2RlIjoic2VxdWVuY2VEaWFncmFtXG4gICAgcGFydGljaXBhbnQgWiBhcyBBZ2VudGxlc3MgQWRhcHRlclxuICAgIHBhcnRpY2lwYW50IEIgYXMgUG9saWN5IENvbnRyYWN0XG4gICAgcGFydGljaXBhbnQgb3RjIGFzIFBpbmcgRmVkZXJhdGUgQVBJXG4gICAgQiAtPj4gWjogL1BPU1QgUmVmSUQgPSB7UGlja3VwUmVmSUR9IFJlc3VtZVBhdGg9e3Jlc3VtZVBhdGh9XG4gICAgbm90ZSBsZWZ0IG9mIFo6IFJlZklEIGlzIGEgb25lLXRpbWUgdG9rZW5cbiAgICBaIC0-PiBvdGM6IEdFVCAvZXh0L3JlZi9waWNrdXA_UkVGPXtQaWNrdXBSZWZJRH1cbiAgICBvdGMgLS0-PiBaOiBSZXR1cm4gSlNPTiBwYXlsb2FkIG9mIGN1cnJlbnQgc3RhdGUgb2YgdXNlclxuICAgIFogLT4-IFo6IERvIHNvbWV0aGluZyB3aXRoIHRoaXMgaW5mb3JtYXRpb25cbiAgICBaIC0-PiBaOiBQZXJoYXBzIHNob3cgdGhlIHVzZXIgc29tZSBVSVxuICAgIFogLT4-IG90YzogU2VuZCBpbmZvcm1hdGlvbiBhYm91dCB0aGUgaWRlbnRpdHkgYmFjayB0byBQaW5nIElkZW50aXR5IC9QT1NUIC9leHQvcmVmL2Ryb3BvZmZcbiAgICBvdGMgLS0-PiBaOiBSZXR1cm4gYSBkcm9wb2ZmIHJlZklEIHtEcm9wb2ZmUmVmSUR9XG4gICAgWiAtPj4gQjogMzAyIC9QT1NUIHtyZXN1bWVQYXRofT9SRUY9e0Ryb3BvZmZSZWZJRH0gXG4gICAgbm90ZSBsZWZ0IG9mIEI6IFJlc3VtZSBwYXRoIGNhbWUgZnJvbSB0aGUgaW5pdGlhbCBQT1NUIHRvIHRoZSBhZ2VudGxlc3MuIFRoZSBkcm9wb2ZmIFJlZklEIGNhbWUgZnJvbSB0aGUgZHJvcCBvZmYgUmVmSUQuXG4gICAgQiAtPj4gQjogVXNlciBjb250aW51ZXMgb24gdGhlaXIgZmxvdyIsIm1lcm1haWQiOiJ7XG4gIFwidGhlbWVcIjogXCJkYXJrXCJcbn0iLCJ1cGRhdGVFZGl0b3IiOnRydWUsImF1dG9TeW5jIjp0cnVlLCJ1cGRhdGVEaWFncmFtIjpmYWxzZX0)
