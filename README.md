# Laravel5-InputPipes

We all face the problems when user submit a form, and all these form data is a mess, sometime we even wanted to trim the inputs, cast them, and reformat them, in fact it is not the hardest thing in the world, but these small tasks really make our code look lengthy, and InputPipes comes into play.

`$inputs = Pipe::make(Input::only('email', 'name'), ['trim|lower', 'trim|ucword'])->get();`

This single line of code simply the time we spend on formatting input.
