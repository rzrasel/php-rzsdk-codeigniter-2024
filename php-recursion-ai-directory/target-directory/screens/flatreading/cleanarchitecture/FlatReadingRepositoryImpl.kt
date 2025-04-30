package com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture

import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingDto
import com.rzrasel.wordquiz.presentation.screens.flatreading.cleanarchitecture.data.FlatReadingRequest
import retrofit2.HttpException
import java.io.IOException

class FlatReadingRepositoryImpl(private val apiService: FlatReadingApiService): FlatReadingRepository {
    override suspend fun getFlatReading(flatReadingRequest: FlatReadingRequest): NetworkResult<FlatReadingDto> {
        return try {
            val response = apiService.getFlatReading(flatReadingRequest)
            //preferences.saveAuthToken(response.token)
            NetworkResult.Success(response.body())
        } catch (e: IOException){
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            NetworkResult.Error("${e.message}")
        }
    }
    override suspend fun getFlatReadingNext(flatReadingRequest: FlatReadingRequest): NetworkResult<FlatReadingDto> {
        return try {
            val response = apiService.getFlatReadingNext(flatReadingRequest)
            //preferences.saveAuthToken(response.token)
            NetworkResult.Success(response.body())
        } catch (e: IOException){
            NetworkResult.Error("${e.message}")
        } catch (e: HttpException){
            NetworkResult.Error("${e.message}")
        }
    }
}