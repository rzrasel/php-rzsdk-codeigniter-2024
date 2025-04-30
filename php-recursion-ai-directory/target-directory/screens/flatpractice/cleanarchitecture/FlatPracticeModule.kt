package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture

import android.content.Context
import com.rzrasel.wordquiz.network.NetConnectionInterceptor
import com.rzrasel.wordquiz.network.RetrofitClient

object FlatPracticeModule {
    private fun provideApiService(context: Context, userAuthToken: String = ""): FlatPracticeApiService {
        val netConnInterceptor: NetConnectionInterceptor = NetConnectionInterceptor(context)
        return RetrofitClient().buildApi(
            context = context,
            api = FlatPracticeApiService::class.java,
            netConnectionInterceptor = netConnInterceptor,
            authToken = userAuthToken
        )
    }

    private fun provideRepository(context: Context, userAuthToken: String = ""): FlatPracticeRepository {
        val apiService = provideApiService(context, userAuthToken)
        return FlatPracticeRepositoryImpl(apiService)
    }

    fun provideFlatPracticeViewModel(context: Context, userAuthToken: String = ""): FlatPracticeViewModel {
        val repository = provideRepository(context, userAuthToken)
        val flatPracticeRepository = FlatPracticeUseCase(repository)
        return FlatPracticeViewModel(flatPracticeRepository)
    }
}