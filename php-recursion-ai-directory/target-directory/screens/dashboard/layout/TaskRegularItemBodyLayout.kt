package com.rzrasel.wordquiz.presentation.screens.dashboard.layout

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.layout.wrapContentWidth
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.core.graphics.toColorInt
import com.rzrasel.wordquiz.presentation.components.components.ButtonComposable
import com.rzrasel.wordquiz.presentation.components.components.NormalOutlinedButton
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardCategoryModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardTaskItemModel
import com.rzrasel.wordquiz.presentation.screens.data.UserModel
import com.rzrasel.wordquiz.presentation.screens.data.UserTaskItemModel

@Composable
fun TaskRegularItemBodyLayout(
    taskCategoryModel: DashboardCategoryModel,
    taskItemModel: DashboardTaskItemModel,
    userModel: UserModel,
    categoryId: String = "",
    itemId: String = "",
    title: String = "",
    subtitle: String = "",
    itemButtonLabel: String = "",
    onClickTaskItem: (DashboardCategoryModel, DashboardTaskItemModel, UserTaskItemModel)-> Unit,
) {
    Column(
        modifier = Modifier
            .fillMaxWidth(),
    ) {
        Card(
            modifier = Modifier
                .padding(1.dp)
                //.background(Color.Transparent)
                .background(Color("#ffffff".toColorInt()))
                .fillMaxWidth(),
            shape = RoundedCornerShape(10.dp),
            elevation = CardDefaults.cardElevation(4.dp),
        ) {
            Column(
                modifier = Modifier
                    .fillMaxWidth()
                    .background(
                        color = Color("#ffffff".toColorInt()),
                        shape = RoundedCornerShape(4.dp)
                    )
                    .clip(
                        shape = RoundedCornerShape(4.dp)
                    )
                    .padding(8.dp),
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .background(Color("#ffffff".toColorInt())),
                ) {
                    Text(
                        text = title,
                        modifier = Modifier
                            .padding(0.dp)
                            .fillMaxWidth(),
                        fontSize = 14.sp,
                        fontWeight = FontWeight.Bold,
                    )
                    Row(
                        modifier = Modifier
                            .fillMaxWidth(),
                    ) {
                        Column(
                            modifier = Modifier
                                .weight(1.0f, true)
                                .padding(end = 6.dp),
                        ) {
                            Text(
                                text = subtitle,
                                modifier = Modifier
                                    .padding(0.dp)
                                    .fillMaxWidth(),
                                fontSize = 14.sp,
                                fontWeight = FontWeight.Normal,
                            )
                        }
                        Column(
                            modifier = Modifier
                                .wrapContentWidth(),
                        ) {
                            ButtonComposable(
                                modifier = Modifier
                                    .background(Color("#ffffff".toColorInt()))
                                    .wrapContentWidth(),
                                backgroundColor = Color.White,
                                contentColor = Color(0xFF172644),
                                cornerRadius = 6.dp,
                                borderStroke = 0.dp,
                                borderStrokeColor = Color.Transparent,
                                labelModifier = Modifier
                                    .wrapContentWidth(),
                                text = itemButtonLabel,
                                fontWeight = FontWeight.Normal,
                                fontSize = 12,
                                onClick = {
                                    val userTaskItem = UserTaskItemModel(
                                        userAuthToken = userModel.userAuthToken ?: "",
                                        categoryId = categoryId,
                                        categoryType = taskCategoryModel.categoryType ?: "",
                                        itemId = itemId,
                                        accessMode = taskItemModel.accessMode ?: "",
                                    )
                                    onClickTaskItem(taskCategoryModel, taskItemModel, userTaskItem)
                                },
                            )
                            /*NormalOutlinedButton(
                                modifier = Modifier
                                    .wrapContentWidth(),
                                roundedCorner = 6,
                                borderStroke = 1.dp,
                                borderStrokeColor = Color.Red,
                                label = "Test",
                                onClick = {}
                            )*/
                            /*ButtonComposable(
                                modifier = Modifier
                                    .background(Color("#ffffff".toColorInt()))
                                    .wrapContentWidth(),
                                backgroundColor = Color.White,
                                contentColor = Color(0xFF172644),
                                cornerRadius = 6.dp,
                                borderStroke = 0.dp,
                                borderStrokeColor = Color.Transparent,
                                labelModifier = Modifier
                                    .wrapContentWidth(),
                                text = itemButtonLabel,
                                fontWeight = FontWeight.Normal,
                                fontSize = 12,
                                onClick = {
                                    val userTaskItem = UserTaskItemModel(
                                        userAuthToken = userModel.userAuthToken ?: "",
                                        categoryId = categoryId,
                                        categoryType = taskCategoryModel.categoryType,
                                        itemId = itemId,
                                        accessMode = taskItemModel.accessMode,
                                    )
                                    onClickTaskItem(taskCategoryModel, taskItemModel, userTaskItem)
                                },
                            )*/
                            /*NormalOutlinedButton(
                                modifier = Modifier
                                    .wrapContentWidth(),
                                roundedCorner = 6,
                                borderStroke = 1.dp,
                                borderStrokeColor = Color.Red,
                                label = "Test",
                                onClick = {}
                            )*/
                        }
                    }
                }
            }
        }
        Spacer(modifier = Modifier.height(4.dp))
    }
}