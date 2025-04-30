package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import android.content.Context
import com.rzrasel.wordquiz.network.NetConnectionInterceptor
import com.rzrasel.wordquiz.network.RetrofitClient

object DashboardModule {
    private fun provideApiService(context: Context, userAuthToken: String = ""): DashboardApiService {
        val netConnInterceptor: NetConnectionInterceptor = NetConnectionInterceptor(context)
        return RetrofitClient().buildApi(
            context = context,
            api = DashboardApiService::class.java,
            netConnectionInterceptor = netConnInterceptor,
            authToken = userAuthToken
        )
    }

    private fun provideRepository(context: Context, userAuthToken: String = ""): DashboardRepository {
        val apiService = provideApiService(context, userAuthToken)
        return DashboardRepositoryImpl(apiService)
    }

    fun provideDashboardViewModel(context: Context, userAuthToken: String = ""): DashboardViewModel {
        val repository = provideRepository(context, userAuthToken)
        val dashboardUseCase = DashboardUseCase(repository)
        return DashboardViewModel(dashboardUseCase)
    }
}