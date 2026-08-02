<?php

declare(strict_types=1);

it('redirects the old kubernetes post slug to the new one', function () {
    $this->get('/blog/setting-up-a-kubernetes-cluster-with-microk8s')
        ->assertRedirect('/blog/setting-up-kubernetes-with-microk8s');
});

it('redirects the old kubernetes post slug with a permanent status code', function () {
    $this->get('/blog/setting-up-a-kubernetes-cluster-with-microk8s')
        ->assertStatus(301);
});
