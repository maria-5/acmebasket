# Acme Basket – Proof of Concept

This is a simple PHP project that implements a shopping basket system for Acme Widget Co. It supports product pricing, delivery rules, and promotional offers like "Buy one Red Widget, get the second half price".

---

## Requirements

You will need the following software installed:

- Docker
- VS Code or any VS Code–based editor

---

## Getting Started

- Clone the repository

Build and Start Docker Container:
- docker-compose up --build

Install Dependencies:
- docker-compose run --rm php composer install

Run the Application Manually:
- docker-compose run --rm php php index.php

Run testcases:
- docker-compose run --rm php composer test

Run analysis:
- docker-compose run --rm php composer analysis
