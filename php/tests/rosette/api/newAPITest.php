<?php
/**
 * @copyright 2014-2015 Basis Technology Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 **/
namespace rosette\api;

// It is better to use phpunit --bootstrap ./vendor/autoload.php than to play with
// the pathing.
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

/**
 * Class APITest
 * @package rosette\api
 */
class APITest extends \PHPUnit_Framework_TestCase
{
    //use \InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;

    public $testUrl = null;
    public $userKey = null;
    static public $port = '8082';
    static public $IP = '127.0.0.1';

//    public static function setUpBeforeClass()
//    {
//        return;
//        // retrieve an available socket
//        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//        socket_bind($socket, APITest::$IP, 0);
//        socket_getsockname($socket, $socket_address, $socket_port);
//        APITest::$port = $socket_port;
//        socket_close($socket);
//        static::setUpHttpMockBeforeClass(APITest::$port, APITest::$IP);
//    }
//
//    public static function tearDownAfterClass()
//    {
//        return;
//        static::tearDownHttpMockAfterClass();
//    }
//
//    public function testSimpleRequest()
//    {
//        $this->httpMock('GET', '/foo', 'mocked body');
//
//        $this->assertSame('mocked body', file_get_contents($this->testUrl.'/foo'));
//    }
//
//    public function testSilly()
//    {
//        $this->assertTrue(true);
//    }
//
//    public function testAPIConstructor()
//    {
//        $api = new Api($this->userKey);
//        $this->assertEquals('https://api.rosette.com/rest/v1', $api->getServiceUrl());
//        $api = new Api('testKey', 'http://test.url.com');
//        $this->assertEquals('testKey', $api->getUserKey());
//        $this->assertEquals('http://test.url.com', $api->getServiceUrl());
//    }
//
//
//    public function testCheckVersionFalse()
//    {
//        $this->httpMock(
//            'GET',
//            '/info',
//            '{"buildNumber": "cc30824d",
//            "name": "Api API",
//            "version": "0.5.0-SNAPSHOT",
//            "buildTime": "2015.04.30_17:00:41"}'
//        );
//
//        $api = new API($this->userKey, $this->testUrl);
//        $this->setExpectedException('rosette\api\RosetteException', 'The server version is not 1.0');
//        $api->checkVersion('1.0');
//    }
//
//    public function testCheckVersionTrue()
//    {
//        $this->httpMock(
//            'GET',
//            '/info',
//            '{"buildNumber": "cc30824d",
//            "name": "Api API",
//            "version": "0.5.0-SNAPSHOT",
//            "buildTime": "2015.04.30_17:00:41"}'
//        );
//
//        $api = new API($this->userKey, $this->testUrl);
//        $result = $api->checkVersion();
//        $this->assertTrue($result);
//    }
//
//    public function testGet()
//    {
//        $opts = ['http' => ['method'=>"GET", 'header'=>"Accept-language: en\r\n".'user_key: foobar']];
//        $context = stream_context_create($opts);
//        $file = file_get_contents('http://jugmaster.basistech.net:80/rest/v1/info', false, $context);
//    }
//
//    public function testPost()
//    {
//        $rp = new DocumentParameters();
//        $rp->params['content'] = $this->HAM_SENTENCE;
//        $this->hamParams = $rp;
////        $postData = http_build_query($this->hamParams->serializable());
////        $opts['http']['method'] = 'POST';
////        $opts['http']['header'] = "Accept: application/json\r\n"."Accept-Encoding: gzip\r\n"."Content-type: application/json\r\n"."user_key: foo-bar";
////        $opts['http']['content'] = $this->hamParams->asString();
////        //$opts['http']['content'] = '{"language": "eng", "content": "The quick brown fox jumped over the lazy dog. Yes he did."}';
////        $context = stream_context_create($opts);
////        $result = file_get_contents('http://jugmaster.basistech.net:80/rest/v1/tokens/', false, $context);
//
//        $url = 'http://jugmaster.basistech.net:80/rest/v1/tokens/';
//        $header['Accept'] = "application/json";
//        $header['Accept-Encoding'] = "gzip";
//        $header['Content-type'] = "application/json";
//        $header['user_key'] = "test";
//
//        $options['timeout'] = '300';
//
//        $this->tryPost($url, $header, $this->hamParams->serializable(), $options);
//    }
//
//    private function tryPost($url, $headers, $data, $options)
//    {
//        return;
//        $opts['http']['method'] = 'POST';
//        $opts['http']['header'] = $this->headersAsString($headers); //"Accept: application/json\r\n"."Accept-Encoding: gzip\r\n"."Content-type: application/json\r\n"."user_key: foo-bar";
//        $opts['http']['content'] = $data->asString();
//        $opts['http'] = array_merge($opts['http'], $options);
//        //$opts['http']['content'] = '{"language": "eng", "content": "The quick brown fox jumped over the lazy dog. Yes he did."}';
//        $context = stream_context_create($opts);
//        $result = file_get_contents($url, false, $context);
//    }

    /*
     * Find the correct response file from the mock-data directory
     * Used to replace the retryingRequest function for mocking
     */
    private function getMockedRetReq($filename)
    {
        $responseDir = dirname(__FILE__) . '/../../../../mock-data/response/';
        $response = file_get_contents($responseDir . $filename . '.json');
        $response = json_decode($response, true);
        return $response;
    }

    /*
     * Replace the getResponseCode method in the API class for mocking purposes
     */
    private function getStatusCode($filename)
    {
        $responseDir = dirname(__FILE__) . '/../../../../mock-data/response/';
        return intval(file_get_contents($responseDir . $filename . '.status'));
    }

    /*
     * Mock the api so that everything still works except the retryingRequest and getResponseCode methods are overridden
     * setResponseCode and checkVersion are stubbed so they just return null
     * private function setUpApi($userKey)
     */
    private function setUpApi($userKey)
    {
        $api = $this->getMockBuilder('rosette\api\Api')
            ->setConstructorArgs(array($userKey))
            ->setMethods(array('retryingRequest', 'setResponseCode', 'getResponseCode', 'checkVersion'))
            ->getMock();
        $api->expects($this->any())
            ->method('retryingRequest')
            ->will($this->returnValue($this->getMockedRetReq($userKey)));
        $api->expects($this->any())
            ->method('getResponseCode')
            ->will($this->returnValue($this->getStatusCode($userKey)));
        return $api;
    }

    public function testInfo()
    {
        $this->userKey = 'info';
        $api = $this->setUpApi($this->userKey);
        $result = $api->info();
        $this->assertEquals('Rosette API', $result['name']);
        $this->assertEquals("6bafb29d", $result['buildNumber']);
    }

    public function testPing()
    {
        $this->userKey = 'ping';
        $api = $this->setUpApi($this->userKey);
        $result = $api->ping();
        $this->assertEquals('Rosette API at your service', $result['message']);
    }

    /*
     * Get the file body for a request given a partial file name
     */
    private function getRequest($filename)
    {
        $requestDir = dirname(__FILE__) . '/../../../../mock-data/request/';
        $request = file_get_contents($requestDir . $filename . '.json');
        return json_decode($request, true);
    }

    /*
     * Return an array of arrays to be passed to testLanguages
     * Each sub array is of the form [file name (after request/ and before .json), endpoint]
     */
    public function findFiles()
    {
        $requestDir = dirname(__FILE__) . '/../../../../mock-data/request/';
        $pattern = "/.*\/request\/([\w\d]*-[\w\d]*-(.*))\.json/";
        $files = array();
        foreach (glob("$requestDir*.json") as $filename) {
        //foreach (array($requestDir . 'rni-1-matched-name.json') as $filename) {
            preg_match($pattern, $filename, $output_array);
            $files[] = array($output_array[1], $output_array[2]);
        }
        return $files;
    }


    /**
     * Test all endpoints (other than ping and info)
     * @dataProvider findFiles
     */
    public function testEndpoints($filename, $endpoint)
    {
        // Set user key as file name because a real user key is unnecessary for testing
        $this->userKey = $filename; // ex 'eng-sentence-language';
        // Set up a mocked api to test against
        $api = $this->setUpApi($this->userKey);
        $input = $this->getRequest($this->userKey);
        $expected = $this->getMockedRetReq($this->userKey);
        // All endpoints except the name ones take DocumentParameters objects as input
        if (strpos($endpoint,'name') === false) {
            $params = $this->getMockBuilder('rosette\api\DocumentParameters')
                ->setConstructorArgs(array($input))
                ->setMethods(null)
                ->getMock();
        } else if (strpos($endpoint, 'translated') !== false) {
            $params = $this->getMockBuilder('rosette\api\NameTranslationParameters')
                ->setConstructorArgs(array($input))
                ->setMethods(null)
                ->getMock();
        } else if (strpos($endpoint, 'matched') !== false) {
            $name1 = $this->getMockBuilder('rosette\api\Name')
                ->setConstructorArgs(array($input["name1"]["text"]))
                ->setMethods(null)
                ->getMock();
            $name2 = $this->getMockBuilder('rosette\api\Name')
                ->setConstructorArgs(array($input["name2"]["text"]))
                ->setMethods(null)
                ->getMock();
            $params = $this->getMockBuilder('rosette\api\NameMatchingParameters')
                ->setConstructorArgs(array($name1, $name2))
                ->setMethods(null)
                ->getMock();
        }
        // Fill in parameters object with data
        foreach (array_keys($input) as $key) {
            $params->params[$key] = $input[$key];
        }
        // Find the correct call to make and call it.
        // If it does not throw an exception, check that it was not supposed to and if so check that it
        // returns the correct thing.
        // If it throws an exception, check that it was supposed to and if so pass otherwise fail test.
        try {
            if ($endpoint === "categories") $result = $api->categories($params);
            if ($endpoint === "entities") $result = $api->entities($params);
            if ($endpoint === "entities_linked") $result = $api->entities($params, true);
            if ($endpoint === "language") $result = $api->language($params);
            if ($endpoint === "matched-name") $result = $api->matchedName($params);
            if ($endpoint === "morphology_complete") $result = $api->morphology($params);
            if ($endpoint === "sentiment") $result = $api->sentiment($params);
            if ($endpoint === "translated-name") $result = $api->translatedName($params);
            // If there is a "code" key, it means an exception should be thrown
            if (!array_key_exists("code", $expected)) {
                $this->assertEquals($expected, $result);
            }
        } catch (RosetteException $exception) {
            if ($expected["code"] === "unsupportedLanguage") $this->assertTrue(true);
            else $this->assertFalse(true);
        }
    }


//    public function testLanguageInfo()
//    {
//        $this->httpMock('GET', '/language/info', '{
//                "requestId": "7d071890-afa2-432f-b1c6-d15238d1538d",
//                "supportedLanguages": {
//                    "srp": [
//                        "Cyrl",
//                        "Latn"
//                    ],
//                    "jpn": [
//                        "Jpan",
//                        "Kana"
//                    ]
//                },
//                "supportedScripts": {
//                    "Beng": [
//                        "ben"
//                    ],
//                    "Gujr": [
//                        "guj"
//                    ]
//                }
//            }');
//
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->languageInfo();
//        $this->assertNotEquals(0, count($result['supportedLanguages']));
//    }
//
//    public function testLanguage()
//    {
//        $this->httpMock(
//            'POST',
//            '/language',
//            '{
//                  "requestId": "43939f9c-cb20-46c4-a61e-e5527c44b98c",
//                  "languageDetections": [
//                    {
//                      "language": "eng",
//                      "confidence": 0.38971654861172306
//                    },
//                    {
//                      "language": "nld",
//                      "confidence": 0.11256076405554828
//                    },
//                    {
//                      "language": "fra",
//                      "confidence": 0.07878603648280982
//                    },
//                    {
//                      "language": "ita",
//                      "confidence": 0.06586061662286635
//                    },
//                    {
//                      "language": "tur",
//                      "confidence": 0.048501824064942474
//                    },
//                    {
//                      "language": "spa",
//                      "confidence": 0.04773385574027546
//                    },
//                    {
//                      "language": "por",
//                      "confidence": 0.04399321305608022
//                    },
//                    {
//                      "language": "deu",
//                      "confidence": 0.04207307936844298
//                    },
//                    {
//                      "language": "swe",
//                      "confidence": 0.035767768437740266
//                    },
//                    {
//                      "language": "ces",
//                      "confidence": 0.03040476517460798
//                    },
//                    {
//                      "language": "nob",
//                      "confidence": 0.02329398189165085
//                    },
//                    {
//                      "language": "fin",
//                      "confidence": 0.022011995848263813
//                    },
//                    {
//                      "language": "dan",
//                      "confidence": 0.0209754856024916
//                    },
//                    {
//                      "language": "ron",
//                      "confidence": 0.01952603613015959
//                    },
//                    {
//                      "language": "hun",
//                      "confidence": 0.01879402891239734
//                    }
//                  ]
//            }'
//        );
//
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->language($this->hamParams);
//
//        $this->assertNotEquals(0, count($result['languageDetections']));
//    }
//
//    public function testSentences()
//    {
//        $this->httpMock(
//            'POST',
//            '/sentences',
//            '{
//                  "requestId": "c20f7bcd-6e9d-4186-8ae4-ea02f02ef00b",
//                  "sentences": [
//                    "Yes, Ma\'m! ",
//                    "Green eggs and ham?  ",
//                    "I am Sam;  I filter Spam."
//                  ]
//            }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->sentences($this->hamParams);
//
//        $this->assertNotNull($result['sentences']);
//        $this->assertEquals(3, count($result['sentences']));
//    }
//
//    public function testTokens()
//    {
//        $this->httpMock('POST', '/tokens', '{
//          "requestId": "576104ac-499f-4565-834d-9727280110c6",
//          "tokens": [
//            "Yes",
//            ",",
//            "Ma",
//            "\'m",
//            "!",
//            "Green",
//            "eggs",
//            "and",
//            "ham",
//            "?",
//            "I",
//            "am",
//            "Sam",
//            ";",
//            "I",
//            "filter",
//            "Spam",
//            "."
//          ]
//        }');
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->tokens($this->hamParams);
//
//        $this->assertEquals(18, count($result['tokens']));
//    }
//
//    public function testMorphologyFile()
//    {
//        $this->httpMock('POST', '/morphology/parts-of-speech', '{
//          "requestId": "157681cf-49dc-4e6c-bd47-2193929be3da",
//          "posTags": [
//            {
//              "text": "2015",
//              "pos": "GUESS"
//            },
//            {
//              "text": "-",
//              "pos": "GUESS"
//            },
//            {
//              "text": "02",
//              "pos": "GUESS"
//            },
//            {
//              "text": "-",
//              "pos": "GUESS"
//            },
//            {
//              "text": "28",
//              "pos": "GUESS"
//            },
//            {
//              "text": "09",
//              "pos": "GUESS"
//            },
//            {
//              "text": ":",
//              "pos": "GUESS"
//            },
//            {
//              "text": "34",
//              "pos": "GUESS"
//            },
//            {
//              "text": ":",
//              "pos": "GUESS"
//            },
//            {
//              "text": "00",
//              "pos": "GUESS"
//            },
//            {
//              "text": "新华",
//              "pos": "GUESS"
//            },
//            {
//              "text": "网",
//              "pos": "NC"
//            },
//            {
//              "text": "分享",
//              "pos": "V"
//            },
//            {
//              "text": "参与",
//              "pos": "V"
//            },
//            {
//              "text": "沉寂",
//              "pos": "E"
//            },
//            {
//              "text": "两",
//              "pos": "NN"
//            },
//            {
//              "text": "年",
//              "pos": "NC"
//            },
//            {
//              "text": "多",
//              "pos": "A"
//            },
//            {
//              "text": "后",
//              "pos": "NC"
//            },
//            {
//              "text": "，",
//              "pos": "GUESS"
//            },
//            {
//              "text": "“",
//              "pos": "GUESS"
//            },
//            {
//              "text": "二",
//              "pos": "NN"
//            },
//            {
//              "text": "张",
//              "pos": "NC"
//            },
//            {
//              "text": "”",
//              "pos": "GUESS"
//            },
//            {
//              "text": "再度",
//              "pos": "D"
//            },
//            {
//              "text": "开吵",
//              "pos": "GUESS"
//            },
//            {
//              "text": "，",
//              "pos": "GUESS"
//            },
//            {
//              "text": "张",
//              "pos": "NC"
//            },
//            {
//              "text": "艺谋",
//              "pos": "GUESS"
//            },
//            {
//              "text": "御用",
//              "pos": "A"
//            },
//            {
//              "text": "文学",
//              "pos": "NC"
//            },
//            {
//              "text": "策划",
//              "pos": "V"
//            },
//            {
//              "text": "周",
//              "pos": "NC"
//            },
//            {
//              "text": "晓",
//              "pos": "W"
//            },
//            {
//              "text": "枫",
//              "pos": "W"
//            },
//            {
//              "text": "出",
//              "pos": "NM"
//            },
//            {
//              "text": "书",
//              "pos": "NC"
//            },
//            {
//              "text": "，",
//              "pos": "GUESS"
//            },
//            {
//              "text": "揭底",
//              "pos": "V"
//            },
//            {
//              "text": "张",
//              "pos": "NC"
//            },
//            {
//              "text": "伟",
//              "pos": "W"
//            },
//            {
//              "text": "平",
//              "pos": "A"
//            },
//            {
//              "text": "。",
//              "pos": "GUESS"
//            },
//            {
//              "text": "据",
//              "pos": "W"
//            },
//            {
//              "text": "了解",
//              "pos": "NC"
//            },
//            {
//              "text": "，",
//              "pos": "GUESS"
//            },
//            {
//              "text": "张",
//              "pos": "NC"
//            },
//            {
//              "text": "伟平",
//              "pos": "GUESS"
//            },
//            {
//              "text": "在",
//              "pos": "D"
//            },
//            {
//              "text": "圈",
//              "pos": "V"
//            },
//            {
//              "text": "内",
//              "pos": "A"
//            },
//            {
//              "text": "人品",
//              "pos": "NC"
//            },
//            {
//              "text": "争议",
//              "pos": "NC"
//            },
//            {
//              "text": "比较",
//              "pos": "U"
//            },
//            {
//              "text": "大",
//              "pos": "A"
//            },
//            {
//              "text": "，",
//              "pos": "GUESS"
//            },
//            {
//              "text": "更",
//              "pos": "NC"
//            },
//            {
//              "text": "有",
//              "pos": "OC"
//            },
//            {
//              "text": "一些",
//              "pos": "NC"
//            },
//            {
//              "text": "风流",
//              "pos": "A"
//            },
//            {
//              "text": "韵事",
//              "pos": "NC"
//            },
//            {
//              "text": "在",
//              "pos": "D"
//            },
//            {
//              "text": "坊",
//              "pos": "W"
//            },
//            {
//              "text": "间",
//              "pos": "W"
//            },
//            {
//              "text": "流传",
//              "pos": "V"
//            },
//            {
//              "text": "。",
//              "pos": "GUESS"
//            },
//            {
//              "text": "一",
//              "pos": "NN"
//            },
//            {
//              "text": "位",
//              "pos": "NM"
//            },
//            {
//              "text": "毕业",
//              "pos": "V"
//            },
//            {
//              "text": "于",
//              "pos": "NC"
//            },
//            {
//              "text": "北京",
//              "pos": "NP"
//            },
//            {
//              "text": "电影",
//              "pos": "NC"
//            },
//            {
//              "text": "学院",
//              "pos": "A"
//            },
//            {
//              "text": "的",
//              "pos": "OC"
//            }
//          ]
//        }');
//
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//
//        $params = new DocumentParameters();
//        $textPath = dirname(__DIR__).'/api/chinese-example.html';
//        $params->LoadDocumentFile($textPath, RosetteConstants::$DataFormat['HTML']);
//        $result = $api->morphology($params, RosetteConstants::$MorphologyOutput['PARTS_OF_SPEECH']);
//
//        $posTags = [];
//        foreach ($result['posTags'] as $x) {
//            array_push($posTags, $x['pos']);
//        }
//        $posTags = array_slice($posTags, 0, count($this->CHINESE_HEAD_TAGS));
//        $this->assertSame(
//            array_diff($this->CHINESE_HEAD_TAGS, $posTags),
//            array_diff($posTags, $this->CHINESE_HEAD_TAGS)
//        );
//    }
//
//    public function testMorphology()
//    {
//        $this->httpMock(
//            'POST',
//            '/morphology/complete',
//            '{
//              "requestId": "fdeaa5ee-5d5f-4b1f-92c2-6ad469896be5",
//              "posTags": [
//                {
//                  "text": "Yes",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": ",",
//                  "pos": "CM"
//                },
//                {
//                  "text": "Ma",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "\'m",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "!",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "Green",
//                  "pos": "ADJ"
//                },
//                {
//                  "text": "eggs",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "and",
//                  "pos": "COORD"
//                },
//                {
//                  "text": "ham",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "?",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "am",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "Sam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ";",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "filter",
//                  "pos": "VI"
//                },
//                {
//                  "text": "Spam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ".",
//                  "pos": "SENT"
//                }
//              ],
//              "lemmas": [
//                {
//                  "text": "Yes",
//                  "lemma": "yes"
//                },
//                {
//                  "text": ",",
//                  "lemma": ","
//                },
//                {
//                  "text": "Ma",
//                  "lemma": "ma"
//                },
//                {
//                  "text": "\'m",
//                  "lemma": "be"
//                },
//                {
//                  "text": "!",
//                  "lemma": "!"
//                },
//                {
//                  "text": "Green",
//                  "lemma": "green"
//                },
//                {
//                  "text": "eggs",
//                  "lemma": "egg"
//                },
//                {
//                  "text": "and",
//                  "lemma": "and"
//                },
//                {
//                  "text": "ham",
//                  "lemma": "ham"
//                },
//                {
//                  "text": "?",
//                  "lemma": "?"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "am",
//                  "lemma": "be"
//                },
//                {
//                  "text": "Sam",
//                  "lemma": "Sam"
//                },
//                {
//                  "text": ";",
//                  "lemma": ";"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "filter",
//                  "lemma": "filter"
//                },
//                {
//                  "text": "Spam",
//                  "lemma": "Spam"
//                },
//                {
//                  "text": ".",
//                  "lemma": "."
//                }
//              ],
//              "compounds": [],
//              "hanReadings": []
//            }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->morphology($this->hamParams);
//        $posTags = [];
//        foreach ($result['posTags'] as $x) {
//            array_push($posTags, $x['pos']);
//        }
//        $this->assertSame(
//            array_diff($this->MORPHO_EXPECTED_POSES, $posTags),
//            array_diff($posTags, $this->MORPHO_EXPECTED_POSES)
//        );
//    }
//
//    public function testMorphologyUnspecified()
//    {
//        $this->httpMock('POST', '/morphology/complete', '{
//              "requestId": "fdeaa5ee-5d5f-4b1f-92c2-6ad469896be5",
//              "posTags": [
//                {
//                  "text": "Yes",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": ",",
//                  "pos": "CM"
//                },
//                {
//                  "text": "Ma",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "\'m",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "!",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "Green",
//                  "pos": "ADJ"
//                },
//                {
//                  "text": "eggs",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "and",
//                  "pos": "COORD"
//                },
//                {
//                  "text": "ham",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "?",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "am",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "Sam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ";",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "filter",
//                  "pos": "VI"
//                },
//                {
//                  "text": "Spam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ".",
//                  "pos": "SENT"
//                }
//              ],
//              "lemmas": [
//                {
//                  "text": "Yes",
//                  "lemma": "yes"
//                },
//                {
//                  "text": ",",
//                  "lemma": ","
//                },
//                {
//                  "text": "Ma",
//                  "lemma": "ma"
//                },
//                {
//                  "text": "\'m",
//                  "lemma": "be"
//                },
//                {
//                  "text": "!",
//                  "lemma": "!"
//                },
//                {
//                  "text": "Green",
//                  "lemma": "green"
//                },
//                {
//                  "text": "eggs",
//                  "lemma": "egg"
//                },
//                {
//                  "text": "and",
//                  "lemma": "and"
//                },
//                {
//                  "text": "ham",
//                  "lemma": "ham"
//                },
//                {
//                  "text": "?",
//                  "lemma": "?"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "am",
//                  "lemma": "be"
//                },
//                {
//                  "text": "Sam",
//                  "lemma": "Sam"
//                },
//                {
//                  "text": ";",
//                  "lemma": ";"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "filter",
//                  "lemma": "filter"
//                },
//                {
//                  "text": "Spam",
//                  "lemma": "Spam"
//                },
//                {
//                  "text": ".",
//                  "lemma": "."
//                }
//              ],
//              "compounds": [],
//              "hanReadings": []
//            }');
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->morphology($this->hamParamsU);
//        $posTags = [];
//        foreach ($result['posTags'] as $x) {
//            array_push($posTags, $x['pos']);
//        }
//        $this->assertSame(
//            array_diff($this->MORPHO_EXPECTED_POSES, $posTags),
//            array_diff($posTags, $this->MORPHO_EXPECTED_POSES)
//        );
//    }
//
//    public function testMorphologyPseudoHTML()
//    {
//        $this->httpMock(
//            'POST',
//            '/morphology/parts-of-speech',
//            '{
//              "requestId": "fdeaa5ee-5d5f-4b1f-92c2-6ad469896be5",
//              "posTags": [
//                {
//                  "text": "Yes",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": ",",
//                  "pos": "CM"
//                },
//                {
//                  "text": "Ma",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "\'m",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "!",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "Green",
//                  "pos": "ADJ"
//                },
//                {
//                  "text": "eggs",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "and",
//                  "pos": "COORD"
//                },
//                {
//                  "text": "ham",
//                  "pos": "NOUN"
//                },
//                {
//                  "text": "?",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "am",
//                  "pos": "VBPRES"
//                },
//                {
//                  "text": "Sam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ";",
//                  "pos": "SENT"
//                },
//                {
//                  "text": "I",
//                  "pos": "PRONPERS"
//                },
//                {
//                  "text": "filter",
//                  "pos": "VI"
//                },
//                {
//                  "text": "Spam",
//                  "pos": "PROP"
//                },
//                {
//                  "text": ".",
//                  "pos": "SENT"
//                }
//              ]
//            }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->morphology($this->dtHtmlParams, RosetteConstants::$MorphologyOutput['PARTS_OF_SPEECH']);
//
//        $posTags = [];
//        foreach ($result['posTags'] as $x) {
//            array_push($posTags, $x['pos']);
//        }
//        $this->assertSame(
//            array_diff($this->MORPHO_EXPECTED_POSES, $posTags),
//            array_diff($posTags, $this->MORPHO_EXPECTED_POSES)
//        );
//    }
//
//    public function testMorphologyXHTML()
//    {
//        $this->httpMock(
//            'POST',
//            '/morphology/parts-of-speech',
//            '{
//            "requestId": "82bf8a61-f950-441f-bb04-0675b5d9a7b7",
//              "posTags": [
//                {
//                    "text": "The",
//                  "pos": "DET"
//                },
//                {
//                    "text": "reign",
//                  "pos": "NOUN"
//                },
//                {
//                    "text": "in",
//                  "pos": "PREP"
//                },
//                {
//                    "text": "Spain",
//                  "pos": "PROP"
//                },
//                {
//                    "text": "falls",
//                  "pos": "NOUN"
//                },
//                {
//                    "text": "mainly",
//                  "pos": "ADV"
//                },
//                {
//                    "text": "upon",
//                  "pos": "PREP"
//                },
//                {
//                    "text": "the",
//                  "pos": "DET"
//                },
//                {
//                    "text": "planes",
//                  "pos": "NOUN"
//                },
//                {
//                    "text": ".",
//                  "pos": "SENT"
//                }
//              ]
//            }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->morphology($this->dtXHtmlParams, RosetteConstants::$MorphologyOutput['PARTS_OF_SPEECH']);
//
//        $posTags = [];
//        foreach ($result['posTags'] as $x) {
//            array_push($posTags, $x['pos']);
//        }
//        $this->assertSame(
//            array_diff($this->MORPHOX_EXPECTED_POSES, $posTags),
//            array_diff($posTags, $this->MORPHOX_EXPECTED_POSES)
//        );
//    }
//
//    public function testMorphologyLemmas()
//    {
//        $this->httpMock(
//            'POST',
//            '/morphology/lemmas',
//            '{
//              "requestId": "7adcaab7-10d2-4f72-b8e1-ae3d38a9d34f",
//              "lemmas": [
//                {
//                  "text": "Yes",
//                  "lemma": "yes"
//                },
//                {
//                  "text": ",",
//                  "lemma": ","
//                },
//                {
//                  "text": "Ma",
//                  "lemma": "ma"
//                },
//                {
//                  "text": "\'m",
//                  "lemma": "be"
//                },
//                {
//                  "text": "!",
//                  "lemma": "!"
//                },
//                {
//                  "text": "Green",
//                  "lemma": "green"
//                },
//                {
//                  "text": "eggs",
//                  "lemma": "egg"
//                },
//                {
//                  "text": "and",
//                  "lemma": "and"
//                },
//                {
//                  "text": "ham",
//                  "lemma": "ham"
//                },
//                {
//                  "text": "?",
//                  "lemma": "?"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "am",
//                  "lemma": "be"
//                },
//                {
//                  "text": "Sam",
//                  "lemma": "Sam"
//                },
//                {
//                  "text": ";",
//                  "lemma": ";"
//                },
//                {
//                  "text": "I",
//                  "lemma": "I"
//                },
//                {
//                  "text": "filter",
//                  "lemma": "filter"
//                },
//                {
//                  "text": "Spam",
//                  "lemma": "Spam"
//                },
//                {
//                  "text": ".",
//                  "lemma": "."
//                }
//              ]
//            }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->morphology($this->hamParams, RosetteConstants::$MorphologyOutput['LEMMAS']);
//
//        $lemmas = [];
//        foreach ($result['lemmas'] as $x) {
//            array_push($lemmas, $x['lemma']);
//        }
//
//        $this->assertSame(
//            array_diff($this->MORPHO_EXPECTED_LEMMAS, $lemmas),
//            array_diff($lemmas, $this->MORPHO_EXPECTED_LEMMAS)
//        );
//    }
//
//    public function testEntities()
//    {
//        $this->httpMock(
//            'POST',
//            '/entities',
//            '{
//              "requestId": "c68785a9-840e-471f-aee0-a72020753071",
//              "entities": [
//                {
//                  "indocChainId": 0,
//                  "type": "PERSON",
//                  "mention": "Rabindranath Tagore",
//                  "normalized": "Rabindranath Tagore",
//                  "count": 1,
//                  "confidence": 0.03516519069671631
//                },
//                {
//                  "indocChainId": 1,
//                  "type": "PERSON",
//                  "mention": "Charu",
//                  "normalized": "Charu",
//                  "count": 1,
//                  "confidence": 0.010698139667510986
//                },
//                {
//                  "indocChainId": 2,
//                  "type": "PERSON",
//                  "mention": "Bankim Chandra Chatterjee",
//                  "normalized": "Bankim Chandra Chatterjee",
//                  "count": 1,
//                  "confidence": 0.024974822998046875
//                }
//              ]
//            }'
//        );
//
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->entities($this->tagParams);
//
//        $this->assertEquals(3, count($result['entities']));
//    }
//
//    public function testCategoriesURI()
//    {
//        $this->httpMock(
//            'POST',
//            '/categories',
//            '{
//          "requestId": "20fbb1f4-db58-420f-83bc-3ccaab341da6",
//          "categories": [
//            {
//              "label": "TECHNOLOGY_AND_COMPUTING",
//              "confidence": 0.32552682365646807
//            }
//          ]
//        }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->categories($this->uriParams);
//
//        $categories = [];
//        foreach ($result['categories'] as $x) {
//            array_push($categories, $x['label']);
//        }
//        $this->assertTrue(in_array('TECHNOLOGY_AND_COMPUTING', $categories));
//    }
//
//    public function testCategories()
//    {
//        $this->httpMock(
//            'POST',
//            '/categories',
//            '{
//          "requestId": "f4855026-231e-4dc2-95aa-f87b91067837",
//          "categories": [
//            {
//              "label": "TECHNOLOGY_AND_COMPUTING",
//              "confidence": 0.06540835747410939
//            }
//          ]
//        }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//
//        $result = $api->categories($this->hamParams);
//
//        $this->assertEquals(1, count($result['categories']));
//    }
//
//    public function testSentiment()
//    {
//        $this->httpMock(
//            'POST',
//            '/sentiment',
//            '{
//          "requestId": "ecfee79c-65f3-4ac9-ac8c-65e9f46d4ea9",
//          "sentiment": [
//            {
//              "label": "pos",
//              "confidence": 0.5851911883167845
//            },
//            {
//              "label": "neg",
//              "confidence": 0.4148088116832155
//            }
//          ]
//        }'
//        );
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//        $result = $api->sentiment($this->hamParams);
//
//        $a = $result['sentiment'];
//        uasort(
//            $a,
//            function ($a1, $a2) {
//                if ($a1['confidence'] == $a2['confidence']) {
//                    return 0;
//                }
//                return $a1['confidence'] > $a2['confidence'] ? -1 : 1;
//            }
//        );
//
//        $this->assertEquals('pos', $a[0]['label']);
//    }
//
//    public function testTranslatedNameFrom()
//    {
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//
//        $this->httpMock(
//            'POST',
//            '/translated-name',
//            '{
//          "requestId": "e887459b-7678-4c8a-92a5-ad0333b611a4",
//          "result": {
//            "sourceScript": "Hani",
//            "sourceLanguageOfOrigin": "zho",
//            "sourceLanguageOfUse": "zho",
//            "translation": "xi jinping",
//            "targetLanguage": "eng",
//            "targetScript": "Latn",
//            "targetScheme": "HYPY",
//            "confidence": 1
//          }
//        }'
//        );
//        $params = new NameTranslationParameters();
//        $params->Set('name', '习近平');
//        $params->Set('entityType', 'PERSON');
//        $params->Set('targetLanguage', 'eng');
//        $result = $api->translatedName($params);
//        $result = $result['result'];
//        $this->assertEquals('Xi Jinping', $result['translation'], 'translation', 0.0, 10, false, true);
//        $this->assertEquals('zho', $result['sourceLanguageOfUse']);
//        $this->assertEquals('HYPY', $result['targetScheme']);
//
//    }
//
//    public function testTranslatedNameTo()
//    {
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//
//        $this->httpMock(
//            'POST',
//            '/translated-name',
//            '{
//          "requestId": "a54c4fce-b2bf-464c-8db5-312c626f6042",
//          "result": {
//            "sourceScript": "Latn",
//            "sourceLanguageOfOrigin": "eng",
//            "sourceLanguageOfUse": "eng",
//            "translation": "国铬普舍",
//            "targetLanguage": "zho",
//            "targetScript": "Hani",
//            "targetScheme": "NATIVE",
//            "confidence": 0.005555555555555556
//          }
//        }'
//        );
//        $params = new NameTranslationParameters();
//        $params->Set('name', 'George Bush');
//        $params->Set('entityType', 'PERSON');
//        $params->Set('sourceScript', 'Latn');
//        $params->Set('sourceLanguageOfOrigin', 'eng');
//        $params->Set('targetLanguage', 'zho');
//        $params->Set('targetScript', 'Hani');
//        $params->Set('targetScheme', 'NATIVE');
//        $result = $api->translatedName($params);
//        $result = $result['result'];
//        $this->assertEquals('国铬普舍', $result['translation']);
//    }
//
//    public function testMatchedName()
//    {
//        $api = new API($this->userKey, $this->testUrl);
//        // for testing, set version_checked to true, otherwise, the mock will fail because the endpoint "/info" does
//        // not exist.
//        $api->setVersionChecked(true);
//
//        $this->httpMock(
//            'POST',
//            '/matched-name',
//            '{
//          "requestId": "a4c147e0-1712-4c8c-a80c-42e8b23dfe81",
//          "result": {
//            "score": 0.8722986247544204
//          }
//        }'
//        );
//        $params = new NameMatchingParameters(new Name("Michael Smith"), new Name("Mike Smitty"));
//        $result = $api->matchedName($params);
//        $result = $result['result'];
//        $this->assertEquals(0.8722986247544204, $result['score']);
//    }
}
