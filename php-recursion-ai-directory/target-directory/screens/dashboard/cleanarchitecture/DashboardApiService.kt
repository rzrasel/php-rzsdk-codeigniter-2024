package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardDto
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Query

interface DashboardApiService {
    @GET("dashboard/")
    suspend fun getDashboard(
    ): Response<DashboardDto>

    @POST("dashboard/")
    suspend fun dashboard(
        @Body dashboardRequest: DashboardRequest
    ): Response<DashboardDto>
}