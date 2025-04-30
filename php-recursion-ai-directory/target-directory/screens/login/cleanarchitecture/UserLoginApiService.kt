package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginDto
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.POST

interface UserLoginApiService {
    @POST("login/")
    suspend fun userLogin(
        @Body loginRequest: UserLoginRequest
    ): Response<UserLoginDto>
}