<?php

it('leitet die Wurzel-Route auf das Admin-Panel weiter', function () {
    $this->get('/')->assertRedirect('/admin');
});
