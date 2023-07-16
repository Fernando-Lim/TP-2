<?php

namespace App\Http\Custom;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\InteractsWithTime;

class CustomRateLimiter extends RateLimiter
{
    use InteractsWithTime;

    public function attempt($key, $maxAttempts, Closure $callback, $decaySeconds = 30)
    {
        
        if ($this->tooManyAttempts($key, $maxAttempts)) {
            return false;
        }

        if (is_null($result = $callback())) {
            $result = true;
        }

        return tap($result, function () use ($key, $decaySeconds) {
            $this->hit($key, $decaySeconds);
        });
    }
    public function hit($key, $decaySeconds = 30)
    {
        $key = $this->cleanRateLimiterKey($key);

        $this->cache->add(
            $key.':timer', $this->availableAt($decaySeconds), $decaySeconds
        );

        $added = $this->cache->add($key, 0, $decaySeconds);

        $hits = (int) $this->cache->increment($key);

        if (! $added && $hits == 1) {
            $this->cache->put($key, 1, $decaySeconds);
        }

        return $hits;
    }

}
