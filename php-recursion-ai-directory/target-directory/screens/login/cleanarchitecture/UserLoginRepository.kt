package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginDto
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginRequest

interface UserLoginRepository {
    //suspend fun userLoginRemote(loginRequest: UserLoginRequest): Flow<NetworkResult<UserLoginDto>>
    suspend fun userLoginRemote(loginRequest: UserLoginRequest): NetworkResult<UserLoginDto>
    fun saveUserLoginSession(userLoginModel: UserLoginModel)
    fun getUserLoginSession(): UserLoginModel?
}