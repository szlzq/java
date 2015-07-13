/******************************************************************************
 ** Copyright (c) 2014-2015 Basis Technology Corporation.
 **
 ** Licensed under the Apache License, Version 2.0 (the "License");
 ** you may not use this file except in compliance with the License.
 ** You may obtain a copy of the License at
 **
 **     http://www.apache.org/licenses/LICENSE-2.0
 **
 ** Unless required by applicable law or agreed to in writing, software
 ** distributed under the License is distributed on an "AS IS" BASIS,
 ** WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 ** See the License for the specific language governing permissions and
 ** limitations under the License.
 ******************************************************************************/

package com.basistech.rosette.apimodel.jackson;

import java.io.IOException;

import com.basistech.rosette.apimodel.SentimentModel;
import com.fasterxml.jackson.core.JsonParser;
import com.fasterxml.jackson.databind.DeserializationContext;
import com.fasterxml.jackson.databind.deser.std.StdDeserializer;

/**
 * Arrange for {@link com.basistech.rosette.apimodel.SentimentModel} to serialize as its code.
 */
public class SentimentModelDeserializer extends StdDeserializer<SentimentModel> {

    public SentimentModelDeserializer() {
        super(SentimentModel.class);
    }

    @Override
    public SentimentModel deserialize(JsonParser jp, DeserializationContext ctxt) throws IOException {
        String code = jp.getText();
        return SentimentModel.forValue(code);
    }
}