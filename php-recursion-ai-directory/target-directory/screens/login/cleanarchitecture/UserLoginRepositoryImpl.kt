package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import android.content.SharedPreferences
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginDto
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginRequest
import retrofit2.HttpException
import java.io.IOException

class UserLoginRepositoryImpl(private val apiService: UserLoginApiService, private val sharedPreferences: SharedPreferences): UserLoginRepository {
    /*override suspend fun userLoginRemote(loginRequest: UserLoginRequest): Flow<NetworkResult<UserLoginDto>> = flow<NetworkResult<UserLoginDto>> {
        emit(NetworkResult.Loading())
        with(apiService.userLogin(loginRequest)) {
            if (isSuccessful) {
                //Log.i("DEBUG_LOG", "isSuccessful Data ${this.body().toString()}")
                emit(NetworkResult.Success(this.body()))
                *//*this.body()?.id?.let {
                    catsDetailsDatabaseHelper.insertFavCatImageRelation(
                        it, favCat.imageId
                    )
                }*//*
            } else {
                emit(NetworkResult.Error(this.errorBody().toString()))
                //emit(NetworkResult.Error("error"))
            }
        }
    }.catch {
        emit(NetworkResult.Error(it.localizedMessage))
    }*/

    override suspend fun userLoginRemote(loginRequest: UserLoginRequest): NetworkResult<UserLoginDto> {
        return try {
            val response = apiService.userLogin(loginRequest)
            //preferences.saveAuthToken(response.token)
            NetworkResult.Success(response.body())
        } catch (e: IOException){
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            NetworkResult.Error("${e.message}")
        }
    }

    override fun saveUserLoginSession(userLoginModel: UserLoginModel) {
        /*with(sharedPreferences.edit()) {
            putString("user_id", user.id)
            putString("user_name", user.name)
            putString("user_email", user.email)
            apply()
        }*/
    }

    override fun getUserLoginSession(): UserLoginModel? {
        val id = sharedPreferences.getString("user_id", null)
        val name = sharedPreferences.getString("user_name", null)
        val email = sharedPreferences.getString("user_email", null)
        return if (id != null && name != null && email != null) {
            UserLoginModel("error", "Error")
        } else null
    }
}