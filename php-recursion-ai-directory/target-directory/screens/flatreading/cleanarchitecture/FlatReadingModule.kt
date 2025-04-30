package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import android.content.Context
import com.rzrasel.wordquiz.network.NetConnectionInterceptor
import com.rzrasel.wordquiz.network.RetrofitClient

object FlatReadingModule {
    private fun provideApiService(context: Context, userAuthToken: String = ""): FlatReadingApiService {
        val netConnInterceptor: NetConnectionInterceptor = NetConnectionInterceptor(context)
        return RetrofitClient().buildApi(
            context = context,
            api = FlatReadingApiService::class.java,
            netConnectionInterceptor = netConnInterceptor,
            authToken = userAuthToken
        )
    }

    private fun provideRepository(context: Context, userAuthToken: String = ""): FlatReadingRepository {
        val apiService = provideApiService(context, userAuthToken)
        return FlatReadingRepositoryImpl(apiService)
    }

    fun provideFlatReadingViewModel(context: Context, userAuthToken: String = ""): FlatReadingViewModel {
        val repository = provideRepository(context, userAuthToken)
        val flatReadingRepository = FlatReadingUseCase(repository)
        return FlatReadingViewModel(flatReadingRepository)
    }
}