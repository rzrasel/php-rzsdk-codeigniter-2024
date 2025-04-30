package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import android.content.Context
import android.content.SharedPreferences
import com.rzrasel.wordquiz.network.NetConnectionInterceptor
import com.rzrasel.wordquiz.network.RetrofitClient

object UserLoginModule {
    private fun provideSharedPreferences(context: Context): SharedPreferences {
        return context.getSharedPreferences("app_prefs", Context.MODE_PRIVATE)
    }

    private fun provideApiService(context: Context): UserLoginApiService {
        val netConnInterceptor: NetConnectionInterceptor = NetConnectionInterceptor(context)
        return RetrofitClient().buildApi(context, UserLoginApiService::class.java, netConnInterceptor)
    }

    private fun provideRepository(context: Context): UserLoginRepository {
        val sharedPreferences = provideSharedPreferences(context)
        val apiService = provideApiService(context)
        return UserLoginRepositoryImpl(apiService, sharedPreferences)
    }

    fun provideLoginViewModel(context: Context): UserLoginViewModel {
        val repository = provideRepository(context)
        val loginUseCase = UserLoginUseCase(repository)
        return UserLoginViewModel(loginUseCase)
    }
}