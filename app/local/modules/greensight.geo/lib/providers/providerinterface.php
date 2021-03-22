<?php

namespace Greensight\Geo\Providers;

interface ProviderInterface
{
    public function search($ip, $params = []);

    public function updateDatabase($disablePhpLimits = true);
}