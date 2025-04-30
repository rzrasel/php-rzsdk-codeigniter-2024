package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardRequest
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.toDashboardModel

class DashboardUseCase(private val repository: DashboardRepository) {
    suspend fun execute(userAuthToken: String?): NetworkResult<DashboardModel> {
        val userAuthTokenVal: String = userAuthToken ?: ""
        val dashboardRequest = DashboardRequest(
            userAuthToken = userAuthTokenVal.trim(),
        )

        //repository.saveUserLoginSession(remoteUser)
        //return NetworkResult.Success(remoteUser.data?.toDashboardModel())
        return when(val remoteUser = repository.getDashboard(dashboardRequest)) {
            is NetworkResult.Loading -> {
                NetworkResult.Loading()
            }
            is NetworkResult.Error -> {
                NetworkResult.Error(remoteUser.message)
            }
            is NetworkResult.Success -> {
                NetworkResult.Success(remoteUser.data?.toDashboardModel())
            }
        }
    }
}