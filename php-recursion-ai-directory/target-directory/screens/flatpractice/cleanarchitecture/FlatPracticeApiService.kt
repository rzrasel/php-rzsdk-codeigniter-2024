package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeDto
import com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data.FlatPracticeRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.POST

interface FlatPracticeApiService {
    @POST("get-questions/")
    suspend fun getFlatPractice(
        @Body flatPracticeRequest: FlatPracticeRequest
    ): Response<FlatPracticeDto>
    @POST("get-questions/")
    suspend fun getFlatPracticeNext(
        @Body flatPracticeRequest: FlatPracticeRequest
    ): Response<FlatPracticeDto>
}