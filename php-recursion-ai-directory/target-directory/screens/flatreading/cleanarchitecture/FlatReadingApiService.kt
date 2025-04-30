package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingDto
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.POST

interface FlatReadingApiService {
    //@POST("flat-reading.php")
    @POST("get-questions/")
    suspend fun getFlatReading(
        @Body flatReadingRequest: FlatReadingRequest
    ): Response<FlatReadingDto>
    @POST("flat-reading-next.php")
    suspend fun getFlatReadingNext(
        @Body flatReadingRequest: FlatReadingRequest
    ): Response<FlatReadingDto>
}