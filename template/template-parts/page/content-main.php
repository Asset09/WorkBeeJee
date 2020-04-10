<?php
global $_page;
?>
<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
    <div class="container">

        <!-- code goes here... -->

        <?php if ($_success) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_success_text; ?>
        </div>
        <hr>
        <?php endif; ?>
        <?php if ($_error) : ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $_error_text; ?>
        </div>
        <hr>
        <?php endif; ?>

        <?php if ($_page == 'index' || $_page == 'admin') : ?>
        <form method="POST" <?php if ($_page == 'admin') :
                                        echo 'action="?save"';
                                    else :
                                        echo 'action="?insert"';
                                    endif; ?> id='form'>
            <div class="form-group">
                <label for="inputName">Имя<abbr aria-label="required" title="Обязательное поле">*</abbr></label>
                <input type="text" class="form-control form-control-sm" id="inputName" aria-describedby="nameHelp"
                    required placeholder="Игорь" autocomplete="on" maxlength="49" minlength="1"
                    title="Напишите имя. Например: Олег. Только буквы и пробелы"
                    pattern="[^±!@£$№#%^&amp;*_+§¡€#¢§¶•ªº«\\/&lt;&gt;?:;|=,0-9]+" name="inputName"
                    <?php if ($_page == 'admin') : ?> value="<?php echo htmlspecialchars(trim($_inputName)); ?>"
                    <?php endif; ?>>
                <small id="nameHelp" class="form-text text-muted">Напишите имя. Например: Олег.</small>
            </div>
            <div class="form-group">
                <label for="inputEmail">E-Mail<abbr aria-label="required" title="Обязательное поле">*</abbr></label>
                <input type="email" class="form-control form-control-sm" id="inputEmail" aria-describedby="emailHelp"
                    placeholder="sasha@example.com" pattern="[a-Z0-9._%+-]+@[a-Z0-9.-]+\.[a-Z]{5,}" maxlength="119"
                    minlength="5" name="inputEmail" autocomplete="on" required <?php if ($_page == 'admin') : ?>
                    value="<?php echo htmlspecialchars(trim($_inputEmail)); ?>" <?php endif; ?>>
                <small id="emailHelp" class="form-text text-muted">Напишите адрес электронной почты. Например:
                    sasha@example.com.</small>
            </div>
            <div class="form-group">
                <label for="textareaTask">Задача<abbr aria-label="required" title="Обязательное поле">*</abbr></label>
                <textarea class="form-control form-control-sm" id="textareaTask" aria-describedby="emailTask"
                    placeholder="Починить дверь" name="textareaTask" autocomplete="on" required><?php if ($_page == 'admin') : ?>
                        <?php echo htmlspecialchars(trim($_textareaTask)); ?>
                    <?php endif; ?></textarea>
                <small id="emailTask" class="form-text text-muted">Напишите задание. Например:
                    купить хлеб.</small>
            </div>

            <?php if ($_page == 'admin') : ?>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="inputDone" name="inputDone" value="checked"
                    <?php if ($_inputDone) {
                                                                                                                                    echo 'checked=""';
                                                                                                                                } ?>>
                <label class="form-check-label" for="inputDone">Статус</label>
            </div>
            <?php endif; ?>

            <?php if ($_page == 'admin') : ?>
            <input type="hidden" name="id" value="<?php echo $_id; ?>">
            <?php endif; ?>
            <?php if ($_page == 'index') : ?>
            <button type="submit" class="btn btn-primary">Добавить задачу</button>
            <?php elseif ($_page == 'admin') : ?>
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <?php endif; ?>
        </form>

        <hr id="tasks">
        <!-- Page navigation START-->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php if ($currPage <= 1) {
                                                    echo 'disabled';
                                                } ?>">
                    <?php
                            printf('<a class="page-link" href="?page=1&sortByHow=%s&sortBy=%s#tasks">Начало</a>', $sortByHow, $sortBy);
                            ?>
                </li>
                <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                            printf('<li class="page-item%s"><a class="page-link" href="?page=%d&sortByHow=%s&sortBy=%s#tasks">%d</a></li>', ($currPage == $i) ? ' active' : '', $i, $sortByHow, $sortBy, $i);
                        }
                        ?>
                <li class="page-item <?php if ($currPage >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                    <a class="page-link" <?php
                                                    printf('href="?page=%d&sortByHow=%s&sortBy=%s#tasks"', $totalPages, $sortByHow, $sortBy);
                                                    ?>>Конец</a>
                </li>
            </ul>
        </nav>
        <!-- Page navigation END-->
        <table class="table table-striped table-hover">
            <caption>Приложение-задачник</caption>
            <thead>
                <tr>
                    <th scope="col"><a
                            href="?page=<?php echo $currPage; ?>&changeSorting=true&sortBy=status&sortByHow=<?php echo $sortByHow; ?>#tasks">Статус</a>
                    </th>
                    <th scope="col"><a
                            href="?page=<?php echo $currPage; ?>&changeSorting=true&sortBy=name&sortByHow=<?php echo $sortByHow; ?>#tasks">Имя</a>
                    </th>
                    <th scope="col"><a
                            href="?page=<?php echo $currPage; ?>&changeSorting=true&sortBy=email&sortByHow=<?php echo $sortByHow; ?>#tasks">E-Mail</a>
                    </th>
                    <th scope="col"><a
                            href="?page=<?php echo $currPage; ?>&changeSorting=true&sortBy=task&sortByHow=<?php echo $sortByHow; ?>#tasks">Задача</a>
                    </th>
                    <?php if ($_page == 'admin') : ?>
                    <th scope="col">Изменить</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                        foreach ($tasks as $task) {
                            if ($task['done']) {
                                printf('<tr class="table-success" data-toggle="tooltip" data-placement="top" title="%s"><th scope="row">✓</th>', $_changedByAdmin);
                            } else {
                                echo '<tr><th scope="row">‒</th>';
                            }
                            printf('<td>%s</td>', htmlspecialchars(trim($task['name'])));
                            printf('<td>%s</td>', htmlspecialchars(trim($task['email'])));
                            printf('<td>%s</td>', htmlspecialchars(trim($task['task'])));
                            if ($_page == 'admin') :
                                printf('<td><a href="?edit=true&id=%d">%s</a></td>', htmlspecialchars(trim($task['id'])), 'Изменить');
                            endif;
                            echo '</tr>';
                        }
                        ?>
            </tbody>
        </table>

        <!-- Page navigation START-->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php if ($currPage <= 1) {
                                                    echo 'disabled';
                                                } ?>">
                    <?php
                            printf('<a class="page-link" href="?page=1&sortByHow=%s&sortBy=%s#tasks">Начало</a>', $sortByHow, $sortBy);
                            ?>
                </li>
                <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                            printf('<li class="page-item%s"><a class="page-link" href="?page=%d&sortByHow=%s&sortBy=%s#tasks">%d</a></li>', ($currPage == $i) ? ' active' : '', $i, $sortByHow, $sortBy, $i);
                        }
                        ?>
                <li class="page-item <?php if ($currPage >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                    <a class="page-link" <?php
                                                    printf('href="?page=%d&sortByHow=%s&sortBy=%s#tasks"', $totalPages, $sortByHow, $sortBy);
                                                    ?>>Конец</a>
                </li>
            </ul>
        </nav>
        <!-- Page navigation END-->
        <?php endif; ?>

        <?php if ($_page == 'login') : ?>
        <div class="row">
            <div class="col-5 align-self-center">
                <form class="form" method="POST" action="?">
                    <h1 class="h3 mb-3 font-weight-normal">Вход</h1>
                    <div class="form-group">
                        <label for="inputName">Имя пользователя <abbr aria-label="required"
                                title="Обязательное поле">*</abbr></label>
                        <input type="user" class="form-control form-control-sm" id="inputName"
                            aria-describedby="userHelp" placeholder="sasha" maxlength="60" minlength="2"
                            name="inputName" autocomplete="on" required>
                        <small id="userHelp" class="form-text text-muted"> Введите своё имя пользователя. Например:
                            sasha. Минимум - 2 символа. Максимум - 60 символов.</small>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Пароль <abbr aria-label="required"
                                title="Обязательное поле">*</abbr></label>
                        <input type="password" class="form-control form-control-sm" id="inputPassword"
                            aria-describedby="passwordHelp" maxlength="60" minlength="2" name="inputPassword"
                            placeholder="Пароль" autocomplete="on" required>
                        <small id="passwordHelp" class="form-text text-muted"> Введите свой пароль. Минимум - 2 символа.
                            Максимум - 60 символов.</small>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
                    <p class="mt-5 mb-3 text-muted"><abbr aria-label="required" title="Обязательное поле">*</abbr> -
                        обязательные поля</p>
                </form>
            </div>
        </div>
        <?php endif; ?>


        <!-- code goes here... -->







    </div>
</main>