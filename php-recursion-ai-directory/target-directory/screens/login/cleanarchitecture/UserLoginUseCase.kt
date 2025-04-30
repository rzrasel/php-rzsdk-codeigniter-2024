package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginRequest
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.toModel

class UserLoginUseCase(private val repository: UserLoginRepository) {
    suspend fun execute(email: String?, password: String?): NetworkResult<UserLoginModel> {
        var emailVal = ""
        email?.let {
            emailVal = email
        }
        val passwordVal: String = password ?: ""

        /*// Check if a session exists
        val localUser = repository.getUserLoginSession()
        if (localUser != null) {
            return localUser // Return session from SharedPreferences
        }

        // If no session, login remotely
        if (email == null || password == null) {
            throw IllegalArgumentException("Email and Password cannot be null for remote login")
        }*/
        val loginRequest = UserLoginRequest(
            email = emailVal.trim(),
            password = passwordVal.trim()
        )
        val remoteUser = repository.userLoginRemote(loginRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }

    suspend operator fun invoke(email: String?, password: String?): NetworkResult<UserLoginModel> {
        val emailVal: String = email ?: ""
        val passwordVal: String = password ?: ""
        //
        val loginRequest = UserLoginRequest(
            email = emailVal.trim(),
            password = passwordVal.trim()
        )
        val remoteUser = repository.userLoginRemote(loginRequest)
        //repository.saveUserLoginSession(remoteUser)
        return NetworkResult.Success(remoteUser.data?.toModel())
    }
}