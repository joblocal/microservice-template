<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ArrayParserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*
         * In order to allow GET query parameters in the following form:
         * filter.property, fields.property, page.property
         *
         * We have to rewrite the $_GET array
         * Keep in mind that nginx will rewrite . parameters to use an _ (underscore)
         * Our public interface will be in the form filter.property not _ (underscore)
        */
        $requestParams = $request->all();

        $filter = preg_grep('/^filter_/', array_keys($requestParams));
        $fields = preg_grep('/^fields_/', array_keys($requestParams));
        $page = preg_grep('/^page_/', array_keys($requestParams));
        $search = preg_grep('/^search_/', array_keys($requestParams));
        $group = preg_grep('/^group_/', array_keys($requestParams));

        foreach ($filter as $aFilter) {
            $requestParams['filter'][str_replace('filter_', '', $aFilter)] = $requestParams[$aFilter];
            unset($requestParams[$aFilter]);
        }
        foreach ($fields as $aField) {
            $requestParams['fields'][str_replace('fields_', '', $aField)] = $requestParams[$aField];
            unset($requestParams[$aField]);
        }
        foreach ($page as $aPage) {
            $requestParams['page'][str_replace('page_', '', $aPage)] = $requestParams[$aPage];
            unset($requestParams[$aPage]);
        }
        foreach ($search as $aSearch) {
            $requestParams['search'][str_replace('search_', '', $aSearch)] = $requestParams[$aSearch];
            unset($requestParams[$aSearch]);
        }
        foreach ($group as $aGroup) {
            $requestParams['group'][str_replace('group_', '', $aGroup)] = $requestParams[$aGroup];
            unset($requestParams[$aGroup]);
        }

        /*
         * In order to parse arrays within our get parameters
         * We need to loop through them and parse the comma separated lists into arrays
        */
        $data = $this->parseData($requestParams, [
            'filter' => [
                'domain',
                'job_category',
                'job_subcategory',
                'ad_type',
                'job_type',
                'company',
                'contract_type',
                'work_experience',
                'education',
                'company_industry',
            ],
            'highlight_fields',
        ]);

        $request->merge($data);

        return $next($request);
    }

    private function parseData($data, $params)
    {
        $data = array_filter($data);
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                if (array_key_exists($key, $data)) {
                    $data[$key] = $this->parseData($data[$key], $param);
                }
            } else {
                if (array_key_exists($param, $data) && ! is_array($data[$param])) {
                    $data[$param] = explode(',', $data[$param]);
                }
            }
        }

        return $data;
    }
}
