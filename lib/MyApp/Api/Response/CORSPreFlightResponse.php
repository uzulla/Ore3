<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

class CORSPreFlightResponse extends Response implements ResponseInterface
{
    public function __construct()
    {
        $this->code = 200;
        $this->header_list["Access-Control-Allow-Headers"] = "Origin, Content-Type, Accept";
        $this->header_list["Access-Control-Allow-Methods"] = "POST, OPTIONS";
        $this->header_list["Access-Control-Max-Age"] = "3600";
    }
}

// CORS PRE FLIGHT request sample
/* curl -H "Origin: http://localhost:8080" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: X-Requested-With" \
  -X OPTIONS --verbose \
  http://localhost:8080/api/hoge
*/
